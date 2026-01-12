<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\Fund;
use App\Persistence\Interfaces\RepositoryManagerInterface;

/**
 * Interface FundsRepositoryInterface
 *
 * Defines the contract for persistence operations related to User Funds (Wallets).
 * Implementations of this interface handle the storage and retrieval of financial balances.
 *
 * @package App\Persistence\Interfaces\Repositories
 */
interface FundsRepositoryInterface extends RepositoryManagerInterface
{
    /**
     * Creates a new fund record for a user.
     *
     * @param array $data Attributes for the fund creation (usually user_id and initial balance).
     * @return Fund|null The created Fund model or null on failure.
     */
    public function create(array $data): ?Fund;

    /**
     * Retrieves the fund record associated with a specific user.
     *
     * @param string $userId The unique identifier of the user owner.
     * @return Fund|null The user's fund record or null if not found.
     */
    public function getFundByUserId(string $userId): ?Fund;

    /**
     * Softly removes a user's fund record.
     *
     * @param string $userId The unique identifier of the user owner.
     * @return void
     */
    public function deleteByUserId(string $userId): void;

    /**
     * Updates the balance or other data of a specific user's fund.
     *
     * @param string $userId The unique identifier of the user owner.
     * @param array $data The fields to be updated (e.g., ['balance' => 100.00]).
     * @return Fund|null The updated Fund model or null on failure.
     */
    public function updateFundByUserId(string $userId, array $data): ?Fund;
}
