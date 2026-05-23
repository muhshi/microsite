<?php

use App\Models\Category;
use App\Models\Microsite;
use App\Models\MicrositeLink;
use App\Models\MicrositeSection;
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

it('renders sibling year tabs when multiple published microsites share a category', function () {
    $category = Category::factory()->create();

    $microsite1 = Microsite::factory()->create([
        'category_id' => $category->id,
        'slug' => 'sektoral-2025',
        'is_published' => true,
        'start_date' => '2025-01-01',
    ]);

    $microsite2 = Microsite::factory()->create([
        'category_id' => $category->id,
        'slug' => 'sektoral-2026',
        'is_published' => true,
        'start_date' => '2026-01-01',
    ]);

    $response = $this->get('/sektoral-2026');
    $response->assertStatus(200);

    // It should see the tabs to both years
    $response->assertSee('Tahun 2025');
    $response->assertSee('Tahun 2026');
});

it('successfully duplicates a microsite with sections and nested links', function () {
    $category = Category::factory()->create();

    $original = Microsite::factory()->create([
        'category_id' => $category->id,
        'title' => 'Original Portal',
        'slug' => 'original-portal',
        'is_published' => true,
    ]);

    $section = MicrositeSection::create([
        'microsite_id' => $original->id,
        'type' => 'grid',
        'order' => 1,
        'config' => ['title' => 'Original Section'],
    ]);

    $parentLink = MicrositeLink::create([
        'microsite_id' => $original->id,
        'section_id' => $section->id,
        'title' => 'Parent Link',
        'url' => 'https://example.com/parent',
        'order' => 1,
    ]);

    $childLink = MicrositeLink::create([
        'microsite_id' => $original->id,
        'section_id' => $section->id,
        'parent_id' => $parentLink->id,
        'title' => 'Child Link',
        'url' => 'https://example.com/child',
        'order' => 1,
    ]);

    // Perform the duplication logic
    $record = $original;
    $newMicrosite = $record->replicate();
    $newMicrosite->title = $record->title.' (Copy)';
    $newMicrosite->slug = $record->slug.'-copy';
    $newMicrosite->is_published = false;
    $newMicrosite->save();

    foreach ($record->sections as $sect) {
        $newSect = $sect->replicate();
        $newSect->microsite_id = $newMicrosite->id;
        $newSect->save();

        $parentLinks = $sect->links()->whereNull('parent_id')->get();

        foreach ($parentLinks as $link) {
            $newLink = $link->replicate();
            $newLink->microsite_id = $newMicrosite->id;
            $newLink->section_id = $newSect->id;
            $newLink->save();

            foreach ($link->children as $child) {
                $newChild = $child->replicate();
                $newChild->microsite_id = $newMicrosite->id;
                $newChild->section_id = $newSect->id;
                $newChild->parent_id = $newLink->id;
                $newChild->save();
            }
        }
    }

    // Assert duplication succeeded
    expect(Microsite::count())->toBe(2);
    expect($newMicrosite->title)->toBe('Original Portal (Copy)');
    expect($newMicrosite->is_published)->toBeFalse();

    // Assert sections duplicated
    expect($newMicrosite->sections)->toHaveCount(1);
    $duplicatedSection = $newMicrosite->sections->first();
    expect($duplicatedSection->type)->toBe('grid');

    // Assert parent links duplicated
    $duplicatedParent = $duplicatedSection->links()->whereNull('parent_id')->first();
    expect($duplicatedParent)->not->toBeNull();
    expect($duplicatedParent->title)->toBe('Parent Link');
    expect($duplicatedParent->microsite_id)->toBe($newMicrosite->id);

    // Assert child links duplicated and correctly nested under new parent
    $duplicatedChild = $duplicatedSection->links()->whereNotNull('parent_id')->first();
    expect($duplicatedChild)->not->toBeNull();
    expect($duplicatedChild->title)->toBe('Child Link');
    expect($duplicatedChild->parent_id)->toBe($duplicatedParent->id);
    expect($duplicatedChild->parent_id)->not->toBe($parentLink->id);
    expect($duplicatedChild->microsite_id)->toBe($newMicrosite->id);
});
