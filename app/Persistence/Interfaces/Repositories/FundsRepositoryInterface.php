<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\Fund;

interface FundsRepositoryInterface
{
    public function create(array $data): ?Fund;
    public function getFundByUserId(string $userId): ?Fund;
    public function deleteByUserId(string $userId): void;
    public function updateFundByUserId(string $userId, array $data): ?Fund;
}
