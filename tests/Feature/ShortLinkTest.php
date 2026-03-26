<?php

use App\Models\Microsite;
use App\Models\ShortLink;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects to original url for active short link', function () {
    $shortLink = ShortLink::factory()->create([
        'code' => 'abc123',
        'original_url' => 'https://example.com/long-url',
        'is_active' => true,
    ]);

    $response = $this->get('/abc123');

    $response->assertRedirect('https://example.com/long-url');
});

it('increments click count on redirect', function () {
    $shortLink = ShortLink::factory()->create([
        'code' => 'click1',
        'original_url' => 'https://example.com',
        'clicks' => 0,
    ]);

    $this->get('/click1');

    expect($shortLink->fresh()->clicks)->toBe(1);
});

it('returns 404 for inactive short link', function () {
    ShortLink::factory()->inactive()->create([
        'code' => 'dead01',
    ]);

    $response = $this->get('/dead01');
    $response->assertStatus(404);
});

it('returns 404 for expired short link', function () {
    ShortLink::factory()->expired()->create([
        'code' => 'exp001',
    ]);

    $response = $this->get('/exp001');
    $response->assertStatus(404);
});

it('returns 404 for non-existent code', function () {
    $response = $this->get('/nonexistent');
    $response->assertStatus(404);
});

it('auto-generates unique code when code is empty', function () {
    $shortLink = ShortLink::create([
        'original_url' => 'https://example.com',
    ]);

    expect($shortLink->code)->not->toBeEmpty();
    expect(strlen($shortLink->code))->toBe(6);
});

it('falls back to microsite when short link code not found', function () {
    $microsite = Microsite::factory()->create([
        'slug' => 'my-site',
        'is_published' => true,
        'template_key' => 'minimal-grid',
    ]);

    $response = $this->get('/my-site');
    $response->assertStatus(200);
    $response->assertSee($microsite->title);
});

it('prioritizes short link over microsite with same code', function () {
    ShortLink::factory()->create([
        'code' => 'same-code',
        'original_url' => 'https://example.com',
    ]);

    Microsite::factory()->create([
        'slug' => 'same-code',
        'is_published' => true,
    ]);

    $response = $this->get('/same-code');
    $response->assertRedirect('https://example.com');
});

it('prevents code that matches microsite slug via model validation', function () {
    Microsite::factory()->create(['slug' => 'existing-site']);

    $code = ShortLink::generateUniqueCode();

    expect($code)->not->toBe('existing-site');
});
