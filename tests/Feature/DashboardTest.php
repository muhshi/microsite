<?php

use App\Filament\Widgets\ActiveMicrositesTable;
use App\Filament\Widgets\MicrositeStatsOverview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('can render dashboard and its custom widgets', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin');
    $response->assertStatus(200);

    // Verify stats overview widget loads
    Livewire::test(MicrositeStatsOverview::class)
        ->assertStatus(200)
        ->assertSee('Total Microsite')
        ->assertSee('Microsite Aktif')
        ->assertSee('Total Klik Short Link');

    // Verify active table widget loads
    Livewire::test(ActiveMicrositesTable::class)
        ->assertStatus(200)
        ->assertSee('Daftar Portal Aktif (Live)');
});
