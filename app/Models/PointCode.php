<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Traits\HasSchemaAccessors;
use App\Traits\HasCustomShortflakePrimary;

/**
 * Model representing a 4-digit code that grants points to a member.
 * 
 * @property int           $id
 * @property int           $staff_id
 * @property string        $code
 * @property int           $points
 * @property Carbon        $expires_at
 * @property int|null      $used_by
 * @property Carbon|null   $used_at
 * @property Carbon        $created_at
 * @property Carbon        $updated_at
 * 
 * @property \App\Models\Staff    $staff
 * @property \App\Models\Member   $usedMember
 * @property \App\Models\Card     $card
 */
class PointCode extends Model
{
    use HasSchemaAccessors, HasCustomShortflakePrimary;

    /**
     * @var string
     */
    protected $table = 'point_codes';

    /**
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'created_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Allow mass assignment of a model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Relationship: which staff member created this code.
     *
     * @return BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Relationship: which card this code will add points to.
     *
     * @return BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Relationship: which member redeemed this code (if any).
     *
     * @return BelongsTo
     */
    public function usedMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'used_by');
    }

    /**
     * Check if the code has expired based on 'expires_at'.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    /**
     * Check if the code has already been used.
     *
     * @return bool
     */
    public function isUsed(): bool
    {
        return !is_null($this->used_at);
    }
}
