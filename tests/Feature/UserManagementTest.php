<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->superAdminRole = Role::create(['name' => config('filament-shield.super_admin_role', 'super_admin')]);
    $this->pegawaiRole = Role::create(['name' => 'pegawai']);

    // Generate permissions for User resource
    $this->viewAnyUser = Permission::create(['name' => 'ViewAny:User']);
    $this->viewUser = Permission::create(['name' => 'View:User']);
    $this->createUser = Permission::create(['name' => 'Create:User']);
    $this->updateUser = Permission::create(['name' => 'Update:User']);
    $this->deleteUser = Permission::create(['name' => 'Delete:User']);
});

it('authorizes super admin to perform all user management actions', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole($this->superAdminRole);

    $targetUser = User::factory()->create();

    expect(Gate::forUser($superAdmin)->allows('viewAny', User::class))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('view', $targetUser))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('create', User::class))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('update', $targetUser))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('delete', $targetUser))->toBeTrue();
});

it('denies pegawai user from accessing user management', function () {
    $pegawai = User::factory()->create();
    $pegawai->assignRole($this->pegawaiRole);

    // Pegawai has no permissions for User resource
    $targetUser = User::factory()->create();

    expect(Gate::forUser($pegawai)->allows('viewAny', User::class))->toBeFalse();
    expect(Gate::forUser($pegawai)->allows('view', $targetUser))->toBeFalse();
    expect(Gate::forUser($pegawai)->allows('create', User::class))->toBeFalse();
    expect(Gate::forUser($pegawai)->allows('update', $targetUser))->toBeFalse();
    expect(Gate::forUser($pegawai)->allows('delete', $targetUser))->toBeFalse();
});

it('prevents user from deleting themselves', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole($this->superAdminRole);

    // Super admin can delete other users
    $otherUser = User::factory()->create();
    expect(Gate::forUser($superAdmin)->allows('delete', $otherUser))->toBeTrue();

    // Super admin cannot delete themselves
    expect(Gate::forUser($superAdmin)->allows('delete', $superAdmin))->toBeFalse();
});
