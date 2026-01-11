<?php

namespace App\Persistence\Implementation\Repositories;

use App\Models\Fund;
use App\Persistence\Interfaces\Repositories\FundsRepositoryInterface;

class FundsRepository implements FundsRepositoryInterface
{
    public function create(array $data): ?Fund
    {
        return Fund::create($data);
    }

    public function getFundByUserId(string $userId): ?Fund
    {
        return Fund::where('user_id', $userId)->first();
    }

    public function deleteByUserId(string $userId): void
    {
        Fund::where('user_id', $userId)->delete();
    }

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

