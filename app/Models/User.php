<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasFactory, Notifiable;

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
        return $this->user_type === 'common';
    }

    public function isMerchant(): bool
    {
        return $this->user_type === 'merchant';
    }
}
