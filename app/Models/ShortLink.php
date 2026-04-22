<?php

namespace App\Models;

use Database\Factories\ShortLinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'code',
    'original_url',
    'clicks',
    'is_active',
    'expires_at',
])]
class ShortLink extends Model
{
    /** @use HasFactory<ShortLinkFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (ShortLink $link) {
            if (empty($link->code)) {
                $link->code = static::generateUniqueCode();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
            'clicks' => 'integer',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isAccessible(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }

    public function incrementClicks(): void
    {
        $this->increment('clicks');
    }

    public static function generateUniqueCode(int $length = 6): string
    {
        do {
            $code = Str::random($length);
        } while (
            static::where('code', $code)->exists()
            || Microsite::where('slug', $code)->exists()
        );

        return $code;
    }

    public function getShortUrl(): string
    {
        return url($this->code);
    }
}
