<?php

use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns 404 if microsite does not exist', function () {
    $response = $this->get('/non-existent-slug');
    $response->assertStatus(404);
});

it('returns 404 if microsite is not published', function () {
    $microsite = Microsite::factory()->create([
        'slug' => 'unpublished-slug',
        'is_published' => false,
    ]);

    $response = $this->get('/unpublished-slug');
    $response->assertStatus(404);
});

it('returns 200 and correct template if microsite is published', function () {
    $microsite = Microsite::factory()->create([
        'slug' => 'published-slug',
        'is_published' => true,
        'template_key' => 'minimal-grid',
    ]);

    $response = $this->get('/published-slug');
    $response->assertStatus(200);
    $response->assertViewIs('templates.minimal-grid');
    $response->assertSee($microsite->title);
});
