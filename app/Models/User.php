<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * Represents a registered entity (Person or Merchant) within the application.
 *
 * @property int $id
 * @property string $full_name
 * @property string $document Unique identifier (CPF/CNPJ).
 * @property string $email
 * @property string $password
 * @property UserType $user_type
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'document',
        'email',
        'password',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_type' => UserType::class,
    ];

    /**
     * Checks if the user is a common (individual) user.
     *
     * @return bool
     */
    public function isCommon(): bool
    {
        return $this->user_type === UserType::COMMON;
    }

    /**
     * Checks if the user is a merchant.
     *
     * @return bool
     */
    public function isMerchant(): bool
    {
        return $this->user_type === UserType::MERCHANT;
    }

    /**
     * Get the wallet/fund associated with the user.
     *
     * @return HasOne
     */
    public function fund()
    {
        return $this->hasOne(Fund::class);
    }

    /**
     * Get all transactions where this user was the payer.
     *
     * @return HasMany
     */
    public function transactionsSent()
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    /**
     * Get all transactions where this user was the recipient.
     *
     * @return HasMany
     */
    public function transactionsReceived()
    {
        return $this->hasMany(Transaction::class, 'recipient_id');
    }
}
