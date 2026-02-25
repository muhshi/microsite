<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicrositeLink extends Model
{
    protected $fillable = [
        'microsite_id',
        'section_id',
        'title',
        'url',
        'icon',
        'badge_text',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (MicrositeLink $link) {
            // Automatically set microsite_id from section when created via nested repeater
            if ($link->section_id && !$link->microsite_id) {
                if ($section = MicrositeSection::find($link->section_id)) {
                    $link->microsite_id = $section->microsite_id;
                }
            }
        });
    }

    public function microsite(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MicrositeSection::class, 'section_id');
    }
}
