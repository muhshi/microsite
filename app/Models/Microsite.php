<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Microsite extends Model
{
    /** @use HasFactory<\Database\Factories\MicrositeFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'category',
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'template_key',
        'theme_color',
        'accent_color',
        'logo_path',
        'hero_title',
        'hero_subtitle',
        'layout_type',
        'is_published',
        'published_at',
        'meta_title',
        'meta_description',
        'og_image_path',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function sections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MicrositeSection::class)->orderBy('order');
    }

    public function links(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MicrositeLink::class)->orderBy('order');
    }
}
