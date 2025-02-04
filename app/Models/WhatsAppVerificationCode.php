<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppVerificationCode extends Model
{
    protected $fillable = [
        'whatsapp',
        'code',
        'used',
        'expires_at'
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the code is valid and not expired
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    /**
     * Mark the code as used
     */
    public function markAsUsed(): void
    {
        $this->used = true;
        $this->save();
    }
}
