<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Microsite extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected string $slugSource = 'title';

    protected $fillable = [
        'category_id',
        'series_id',
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
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_public' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(MicrositeSection::class)->orderBy('order');
    }

    public function links(): HasMany
    {
        return $this->hasMany(MicrositeLink::class)->orderBy('order');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }
}
