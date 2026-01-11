<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'full_name',
        'document',
        'email',
        'password',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_type' => UserType::class,
    ];

    public function isCommon(): bool
    {
        return $this->user_type === UserType::COMMON;
    }

    public function isMerchant(): bool
    {
        return $this->user_type === UserType::MERCHANT;
    }

    public function wallet()
    {
        return $this->hasOne(Fund::class);
    }

    public function transactionsSent()
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    public function transactionsReceived()
    {
        return $this->hasMany(Transaction::class, 'recipient_id');
    }
}
