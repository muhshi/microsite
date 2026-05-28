<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $sourceField = $model->getSlugSourceField();
            if (empty($model->slug) && ! empty($model->$sourceField)) {
                $model->slug = Str::slug($model->$sourceField);
            }
        });
    }

    protected function getSlugSourceField(): string
    {
        return $this->slugSource ?? 'name';
    }
}
