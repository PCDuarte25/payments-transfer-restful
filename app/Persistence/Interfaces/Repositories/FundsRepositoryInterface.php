<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\Fund;

interface FundsRepositoryInterface
{
    public function create(array $data): ?Fund;
    public function deleteByUserId(string $userId): void;
}
