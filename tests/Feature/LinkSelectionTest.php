<?php

use App\Filament\Resources\Links\Pages\CreateLink;
use App\Filament\Resources\Microsites\Pages\EditMicrosite;
use App\Models\Microsite;
use App\Models\MicrositeLink;
use App\Models\MicrositeSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function findComponentByName(array $components, string $name)
{
    foreach ($components as $component) {
        if (method_exists($component, 'getName') && $component->getName() === $name) {
            return $component;
        }
        if (method_exists($component, 'getChildComponents')) {
            $found = findComponentByName($component->getChildComponents(), $name);
            if ($found) {
                return $found;
            }
        }
    }

    return null;
}

it('filters parent links and sections based on selected microsite in LinkResource', function () {
    $user = User::factory()->create();

    $microsite1 = Microsite::factory()->create(['title' => 'Microsite A']);
    $microsite2 = Microsite::factory()->create(['title' => 'Microsite B']);

    $section1 = MicrositeSection::create([
        'microsite_id' => $microsite1->id,
        'type' => 'grid',
        'order' => 1,
    ]);
    $section2 = MicrositeSection::create([
        'microsite_id' => $microsite2->id,
        'type' => 'list',
        'order' => 1,
    ]);

    // Parent link for Microsite A
    $linkA = MicrositeLink::create([
        'microsite_id' => $microsite1->id,
        'section_id' => $section1->id,
        'title' => 'Link A (Parent)',
        'url' => 'https://a.com',
    ]);

    // Parent link for Microsite B
    $linkB = MicrositeLink::create([
        'microsite_id' => $microsite2->id,
        'section_id' => $section2->id,
        'title' => 'Link B (Parent)',
        'url' => 'https://b.com',
    ]);

    $this->actingAs($user);

    $lw = Livewire::test(CreateLink::class);

    // Get parent_id select field and its modifier closure
    $parentSelect = $lw->instance()->form->getComponent('parent_id');
    $ref = new ReflectionProperty($parentSelect, 'modifyRelationshipQueryUsing');
    $ref->setAccessible(true);
    $parentClosure = $ref->getValue($parentSelect);

    // Get section_id select field and its modifier closure
    $sectionSelect = $lw->instance()->form->getComponent('section_id');
    $refSection = new ReflectionProperty($sectionSelect, 'modifyRelationshipQueryUsing');
    $refSection->setAccessible(true);
    $sectionClosure = $refSection->getValue($sectionSelect);

    // 1. Initial State: No microsite selected (should return empty list)
    $query = MicrositeLink::query();
    $parentSelect->evaluate($parentClosure, ['query' => $query, 'record' => null]);
    expect($query->get())->toBeEmpty();

    $querySec = MicrositeSection::query();
    $sectionSelect->evaluate($sectionClosure, ['query' => $querySec]);
    expect($querySec->get())->toBeEmpty();

    // 2. Select Microsite A
    $lw->set('data.microsite_id', $microsite1->id);

    $query = MicrositeLink::query();
    $lw->instance()->form->getComponent('parent_id')->evaluate($parentClosure, ['query' => $query, 'record' => null]);
    $results = $query->get();
    expect($results)->toHaveCount(1);
    expect($results->first()->id)->toBe($linkA->id);

    $querySec = MicrositeSection::query();
    $lw->instance()->form->getComponent('section_id')->evaluate($sectionClosure, ['query' => $querySec]);
    $secResults = $querySec->get();
    expect($secResults)->toHaveCount(1);
    expect($secResults->first()->id)->toBe($section1->id);

    // 3. Select Microsite B
    $lw->set('data.microsite_id', $microsite2->id);

    $query = MicrositeLink::query();
    $lw->instance()->form->getComponent('parent_id')->evaluate($parentClosure, ['query' => $query, 'record' => null]);
    $results = $query->get();
    expect($results)->toHaveCount(1);
    expect($results->first()->id)->toBe($linkB->id);

    $querySec = MicrositeSection::query();
    $lw->instance()->form->getComponent('section_id')->evaluate($sectionClosure, ['query' => $querySec]);
    $secResults = $querySec->get();
    expect($secResults)->toHaveCount(1);
    expect($secResults->first()->id)->toBe($section2->id);
});

it('filters parent links based on current microsite in MicrositeForm edit', function () {
    $user = User::factory()->create();

    $microsite1 = Microsite::factory()->create(['title' => 'Microsite A']);
    $microsite2 = Microsite::factory()->create(['title' => 'Microsite B']);

    $section1 = MicrositeSection::create([
        'microsite_id' => $microsite1->id,
        'type' => 'grid',
        'order' => 1,
    ]);
    $section2 = MicrositeSection::create([
        'microsite_id' => $microsite2->id,
        'type' => 'list',
        'order' => 1,
    ]);

    // Parent link for Microsite A
    $linkA = MicrositeLink::create([
        'microsite_id' => $microsite1->id,
        'section_id' => $section1->id,
        'title' => 'Link A (Parent)',
        'url' => 'https://a.com',
    ]);

    // Parent link for Microsite B
    $linkB = MicrositeLink::create([
        'microsite_id' => $microsite2->id,
        'section_id' => $section2->id,
        'title' => 'Link B (Parent)',
        'url' => 'https://b.com',
    ]);

    $this->actingAs($user);

    $lw = Livewire::test(EditMicrosite::class, [
        'record' => $microsite1->getKey(),
    ]);

    $form = $lw->instance()->form;
    $parentSelect = findComponentByName($form->getComponents(), 'parent_id');

    expect($parentSelect)->not->toBeNull();

    $ref = new ReflectionProperty($parentSelect, 'modifyRelationshipQueryUsing');
    $ref->setAccessible(true);
    $parentClosure = $ref->getValue($parentSelect);

    $query = MicrositeLink::query();
    $parentSelect->evaluate($parentClosure, ['query' => $query, 'record' => null]);
    $results = $query->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->id)->toBe($linkA->id);
});
