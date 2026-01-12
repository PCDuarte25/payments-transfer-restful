<?php

namespace App\Persistence\Implementation\Repositories;

use App\Models\Fund;
use App\Persistence\Implementation\RepositoryManager;
use App\Persistence\Interfaces\Repositories\FundsRepositoryInterface;

/**
 * Class FundsRepository
 *
 * Eloquent implementation of the FundsRepositoryInterface.
 * This class handles direct database interactions for user balances.
 *
 * @package App\Persistence\Implementation\Repositories
 */
class FundsRepository extends RepositoryManager implements FundsRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(array $data): ?Fund
    {
        return Fund::create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function getFundByUserId(string $userId): ?Fund
    {
        return Fund::where('user_id', $userId)->first();
    }

    /**
     * {@inheritDoc}
     */
    public function deleteByUserId(string $userId): void
    {
        Fund::where('user_id', $userId)->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function updateFundByUserId(string $userId, array $data): ?Fund
    {
        $fund = $this->getFundByUserId($userId);

        if ($fund) {
            $fund->update($data);
            return $fund;
        }
        return null;
    }
}

