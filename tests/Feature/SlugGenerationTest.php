<?php

use App\Models\Category;
use App\Models\Microsite;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('automatically generates slug for Category when saved', function () {
    $category = Category::create([
        'name' => 'Kategori Baru Sekali',
        'description' => 'Test',
    ]);

    expect($category->slug)->toBe('kategori-baru-sekali');
});

it('automatically generates slug for Series when saved', function () {
    $series = Series::create([
        'name' => 'Seri Baru Sekali',
        'description' => 'Test',
    ]);

    expect($series->slug)->toBe('seri-baru-sekali');
});

it('automatically generates slug for Microsite when saved', function () {
    $category = Category::factory()->create();

    $microsite = Microsite::create([
        'category_id' => $category->id,
        'title' => 'Portal Survei 2026',
        'description' => 'Test',
        'template_key' => 'minimal-grid',
        'layout_type' => 'grid',
    ]);

    expect($microsite->slug)->toBe('portal-survei-2026');
});
