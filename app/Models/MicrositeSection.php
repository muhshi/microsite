<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function microsite(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function links(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MicrositeLink::class, 'section_id')->orderBy('order');
    }
}
