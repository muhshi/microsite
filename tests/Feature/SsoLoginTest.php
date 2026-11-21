<?php

use App\Filament\Pages\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

it('redirects /login to filament admin login route', function () {
    $response = $this->get('/login');

    $response->assertRedirect(route('filament.admin.auth.login'));
});

it('renders custom filament login page with SIPETRA SSO button and logo', function () {
    $response = $this->get(route('filament.admin.auth.login'));

    $response->assertSuccessful();
    $response->assertSee('Masuk dengan SIPETRA SSO');
    $response->assertSee(route('sipetra.login'));
    $response->assertSee('logo_bps.png');
    $response->assertSee('Opsi Darurat / Pengembang');
});

it('displays flash error and status notifications on login page', function () {
    $response = $this->withSession([
        'error' => 'Login SSO dibatalkan oleh pengguna.',
        'status' => 'Sesi Anda telah diperbarui.',
    ])->get(route('filament.admin.auth.login'));

    $response->assertSuccessful();
    $response->assertSee('Login SSO dibatalkan oleh pengguna.');
    $response->assertSee('Sesi Anda telah diperbarui.');
});

it('activates fallback mode when query parameter fallback=1 is provided', function () {
    Livewire::withQueryParams(['fallback' => '1'])
        ->test(Login::class)
        ->assertSet('showFallback', true);
});

it('defaults fallback mode to false when query parameter is omitted', function () {
    Livewire::withQueryParams([])
        ->test(Login::class)
        ->assertSet('showFallback', false);
});

it('redirects to login with error when sso callback carries an error', function () {
    $response = $this->get('/auth/sipetra/callback?error=access_denied&error_description=Pengguna+membatalkan+otentikasi');

    $response->assertRedirect(route('filament.admin.auth.login'));
    $response->assertSessionHas('error', 'Pengguna membatalkan otentikasi');
});

it('redirects to login with friendly error when sso driver throws exception', function () {
    Socialite::shouldReceive('driver->user')
        ->andThrow(new Exception('Network timeout'));

    $response = $this->get('/auth/sipetra/callback?code=fake-code');

    $response->assertRedirect(route('filament.admin.auth.login'));
    $response->assertSessionHas('error');
});

it('creates local user with pegawai role on successful sso callback', function () {
    $mockUser = Mockery::mock(SocialiteUserContract::class);
    $mockUser->shouldReceive('getId')->andReturn('sso-user-123');
    $mockUser->shouldReceive('getName')->andReturn('Budi Santoso');
    $mockUser->shouldReceive('getEmail')->andReturn('budi@bps.go.id');
    $mockUser->shouldReceive('getRaw')->andReturn([
        'nip' => '198901012010011001',
        'jabatan' => 'Statistisi Ahli Pertama',
    ]);
    $mockUser->token = 'mock-access-token';

    Socialite::shouldReceive('driver->user')
        ->andReturn($mockUser);

    $response = $this->get('/auth/sipetra/callback?code=valid-code');

    $response->assertRedirect('/admin');

    $this->assertDatabaseHas('users', [
        'sipetra_id' => 'sso-user-123',
        'email' => 'budi@bps.go.id',
        'name' => 'Budi Santoso',
        'nip' => '198901012010011001',
    ]);

    $user = User::where('sipetra_id', 'sso-user-123')->first();
    expect($user)->not->toBeNull();
    expect($user->hasRole('pegawai'))->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});
