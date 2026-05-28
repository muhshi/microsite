<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Series extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::saving(function (Series $series) {
            if (empty($series->slug)) {
                $series->slug = Str::slug($series->name);
            }
        });
    }

    public function microsites(): HasMany
    {
        return $this->hasMany(Microsite::class);
    }
}
