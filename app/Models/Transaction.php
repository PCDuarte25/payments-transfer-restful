<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 *
 * Represents an immutable record of a financial transfer between two users.
 *
 * @property int $id
 * @property int $payer_id
 * @property int $recipient_id
 * @property float $amount
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @package App\Models
 */
class Transaction extends Model
{
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     * * Disabled to allow manual control of the 'created_at' field
     * without the 'updated_at' column.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payer_id',
        'recipient_id',
        'amount',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:4',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who sent the funds.
     *
     * @return BelongsTo
     */
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * Get the user who received the funds.
     *
     * @return BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
