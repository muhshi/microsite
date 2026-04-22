<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MicrositeSection extends Model
{
    protected $fillable = [
        'microsite_id',
        'type',
        'order',
        'config',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(MicrositeLink::class, 'section_id')->orderBy('order');
    }
}
