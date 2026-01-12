<?php

namespace Tests\Unit\Application\UseCases\TransactionUseCases\Cases;

use App\Application\UseCases\TransactionUseCases\Cases\CreateTransaction;
use App\Events\TransactionCompleted;
use App\Models\Fund;
use App\Models\Transaction;
use App\Models\User;
use App\Persistence\Implementation\Repositories\FundsRepository;
use App\Persistence\Implementation\Repositories\TransactionsRepository;
use App\Persistence\Implementation\Repositories\UsersRepository;
use App\Services\TransactionAuthorizationService;
use Exception;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

/**
 * Class CreateTransactionTest
 *
 * Unit tests for the CreateTransaction use case.
 * Validates business rules, external authorization integration,
 * and successful state transitions using mocks.
 *
 * @package Tests\Unit\Application\UseCases\TransactionUseCases\Cases
 */
class CreateTransactionTest extends TestCase
{
    /**
     * Sets up the test environment by initializing mocks and the use case.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Initialize Mocks
        $this->usersRepository = Mockery::mock(UsersRepository::class);
        $this->transactionsRepository = Mockery::mock(TransactionsRepository::class);
        $this->fundsRepository = Mockery::mock(FundsRepository::class);
        $this->authorizationService = Mockery::mock(TransactionAuthorizationService::class);
        $this->payer = Mockery::mock(User::class);
        $this->recipient = Mockery::mock(User::class);
        $this->fund = Mockery::mock(Fund::class);
        $this->transaction = Mockery::mock(Transaction::class);


        // Instantiate Use Case
        $this->useCase = new CreateTransaction(
            $this->usersRepository,
            $this->transactionsRepository,
            $this->fundsRepository,
            $this->authorizationService
        );
    }

    /**
     * Cleans up Mockery resources after each test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Tests that an exception is thrown if the payer user is not found.
     *
     * @return void
     */
    public function test_should_throw_exception_when_payer_not_found(): void
    {
        $this->usersRepository
            ->shouldReceive('getFromId')
            ->with(1)
            ->andReturn(null)
            ->once();

        $this->usersRepository
            ->shouldReceive('getFromId')
            ->with(2)
            ->andReturn($this->payer)
            ->once();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Usuário pagador não encontrado.');

        $this->useCase->execute([
            'payer_id' => 1,
            'recipient_id' => 2,
            'amount' => 100,
        ]);
    }

    /**
     * Tests that an exception is thrown if the payer does not have enough funds.
     *
     * @return void
     */
    public function test_should_throw_exception_when_insufficient_balance(): void
    {
        $this->payer->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->payer->shouldReceive('isMerchant')->andReturn(false);
        $this->recipient->shouldReceive('getAttribute')->with('id')->andReturn(2);

        $this->usersRepository->shouldReceive('getFromId')->with(1)->andReturn($this->payer);
        $this->usersRepository->shouldReceive('getFromId')->with(2)->andReturn($this->recipient);

        $this->fund->shouldReceive('setAttribute')->with('balance')->andReturn(50);
        $this->fund->shouldReceive('getAttribute')->with('balance')->andReturn(50);

        $this->fundsRepository->shouldReceive('getFundByUserId')->with(1)->andReturn($this->fund);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Saldo insuficiente para realizar a transação.');

        $this->useCase->execute([
            'payer_id' => 1,
            'recipient_id' => 2,
            'amount' => 100,
        ]);
    }

   /**
     * Tests that an exception is thrown if the external authorization fails.
     *
     * @return void
     */
    public function test_should_throw_exception_when_authorization_fails(): void
    {
        $this->payer->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->payer->shouldReceive('isMerchant')->andReturn(false);
        $this->recipient->shouldReceive('getAttribute')->with('id')->andReturn(2);

        $this->usersRepository->shouldReceive('getFromId')->with(1)->andReturn($this->payer);
        $this->usersRepository->shouldReceive('getFromId')->with(2)->andReturn($this->recipient);

        $this->fundsRepository->shouldReceive('getFundByUserId')->with(1)->andReturn($this->fund);

        $this->fund->shouldReceive('setAttribute')->with('balance')->andReturn(200);
        $this->fund->shouldReceive('getAttribute')->with('balance')->andReturn(200);

        $this->authorizationService->shouldReceive('authorize')->andReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Transação não autorizada pelo serviço externo.');

        $this->useCase->execute([
            'payer_id' => 1,
            'recipient_id' => 2,
            'amount' => 100,
        ]);
    }

    /**
     * Tests the complete successful transaction flow.
     *
     * @return void
     */
    public function test_should_create_transaction_successfully(): void
    {
        Event::fake();

        $this->payer->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->payer->shouldReceive('isMerchant')->andReturn(false);
        $this->recipient->shouldReceive('getAttribute')->with('id')->andReturn(2);

        $this->transactionsRepository->shouldReceive('beginTransaction')->once();
        $this->transactionsRepository->shouldReceive('commitTransaction')->once();

        $this->usersRepository->shouldReceive('getFromId')->with(1)->andReturn($this->payer);
        $this->usersRepository->shouldReceive('getFromId')->with(2)->andReturn($this->recipient);

        $this->fundsRepository->shouldReceive('getFundByUserId')->with(1)->andReturn($this->fund);
        $this->fundsRepository->shouldReceive('getFundByUserId')->with(2)->andReturn($this->fund);

        $this->fundsRepository->shouldReceive('updateFundByUserId')->with(1, ['balance' => 100])->once();
        $this->fundsRepository->shouldReceive('updateFundByUserId')->with(2, ['balance' => 300])->once();

        $this->fund->shouldReceive('setAttribute')->with('balance')->andReturn(200);
        $this->fund->shouldReceive('getAttribute')->with('balance')->andReturn(200);

        $this->transactionsRepository->shouldReceive('create')->andReturn($this->transaction);
        $this->transaction->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->transaction->shouldReceive('getAttribute')->with('payer_id')->andReturn(1);
        $this->transaction->shouldReceive('getAttribute')->with('recipient_id')->andReturn(2);
        $this->transaction->shouldReceive('getAttribute')->with('amount')->andReturn(100);

        $this->authorizationService->shouldReceive('authorize')->andReturn(true);

        $result = $this->useCase->execute([
            'payer_id' => 1,
            'recipient_id' => 2,
            'amount' => 100,
        ]);

        $this->assertEquals($result, [
            'transaction_id' => 1,
            'payer_id' => 1,
            'payer_new_balance' => 200,
            'recipient_id' => 2,
            'recipient_new_balance' => 200,
            'amount' => 100,
        ]);

        Event::assertDispatched(TransactionCompleted::class);
    }
}
