<?php

use App\Models\Category;
use App\Models\Microsite;
use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Reset cached roles and permissions
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    // Create necessary permissions and roles
    $this->superAdminRole = Role::create(['name' => config('filament-shield.super_admin_role', 'super_admin')]);

    $this->viewAnyPermission = Permission::create(['name' => 'ViewAny:Microsite']);
    $this->viewPermission = Permission::create(['name' => 'View:Microsite']);
    $this->createPermission = Permission::create(['name' => 'Create:Microsite']);
    $this->updatePermission = Permission::create(['name' => 'Update:Microsite']);
    $this->deletePermission = Permission::create(['name' => 'Delete:Microsite']);

    $this->updateShortLinkPermission = Permission::create(['name' => 'Update:ShortLink']);
    $this->deleteShortLinkPermission = Permission::create(['name' => 'Delete:ShortLink']);
});

it('sets created_by automatically on Microsite creation for authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = Category::factory()->create();

    $microsite = Microsite::create([
        'category_id' => $category->id,
        'title' => 'My New Portal',
        'template_key' => 'minimal-grid',
        'layout_type' => 'grid',
    ]);

    expect($microsite->created_by)->toBe($user->id);
});

it('sets created_by automatically on ShortLink creation for authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $shortLink = ShortLink::create([
        'original_url' => 'https://google.com',
        'is_active' => true,
    ]);

    expect($shortLink->created_by)->toBe($user->id);
});

it('leaves created_by null if created by guest', function () {
    $category = Category::factory()->create();

    $microsite = Microsite::create([
        'category_id' => $category->id,
        'title' => 'Guest Portal',
        'template_key' => 'minimal-grid',
        'layout_type' => 'grid',
    ]);

    $shortLink = ShortLink::create([
        'original_url' => 'https://google.com',
        'is_active' => true,
    ]);

    expect($microsite->created_by)->toBeNull();
    expect($shortLink->created_by)->toBeNull();
});

it('authorizes super admin to update and delete any microsite', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole($this->superAdminRole);

    $owner = User::factory()->create();
    $category = Category::factory()->create();

    // Create a microsite owned by someone else
    $microsite = Microsite::create([
        'category_id' => $category->id,
        'title' => 'Owner Portal',
        'template_key' => 'minimal-grid',
        'layout_type' => 'grid',
        'created_by' => $owner->id,
    ]);

    // Create a microsite with no owner
    $noOwnerMicrosite = Microsite::create([
        'category_id' => $category->id,
        'title' => 'Legacy Portal',
        'template_key' => 'minimal-grid',
        'layout_type' => 'grid',
        'created_by' => null,
    ]);

    expect(Gate::forUser($superAdmin)->allows('update', $microsite))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('delete', $microsite))->toBeTrue();

    expect(Gate::forUser($superAdmin)->allows('update', $noOwnerMicrosite))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('delete', $noOwnerMicrosite))->toBeTrue();
});

it('authorizes owners to update and delete their own microsite if they have general permissions', function () {
    $owner = User::factory()->create();
    $owner->givePermissionTo($this->updatePermission);
    $owner->givePermissionTo($this->deletePermission);

    $otherUser = User::factory()->create();
    $otherUser->givePermissionTo($this->updatePermission);
    $otherUser->givePermissionTo($this->deletePermission);

    $category = Category::factory()->create();

    $microsite = Microsite::create([
        'category_id' => $category->id,
        'title' => 'Owner Portal',
        'template_key' => 'minimal-grid',
        'layout_type' => 'grid',
        'created_by' => $owner->id,
    ]);

    // Owner can update/delete
    expect(Gate::forUser($owner)->allows('update', $microsite))->toBeTrue();
    expect(Gate::forUser($owner)->allows('delete', $microsite))->toBeTrue();

    // Other user with the same general permission cannot update/delete
    expect(Gate::forUser($otherUser)->allows('update', $microsite))->toBeFalse();
    expect(Gate::forUser($otherUser)->allows('delete', $microsite))->toBeFalse();
});

it('denies regular users to update or delete legacy microsites (null created_by)', function () {
    $user = User::factory()->create();
    $user->givePermissionTo($this->updatePermission);
    $user->givePermissionTo($this->deletePermission);

    $category = Category::factory()->create();

    $microsite = Microsite::create([
        'category_id' => $category->id,
        'title' => 'Legacy Portal',
        'template_key' => 'minimal-grid',
        'layout_type' => 'grid',
        'created_by' => null,
    ]);

    expect(Gate::forUser($user)->allows('update', $microsite))->toBeFalse();
    expect(Gate::forUser($user)->allows('delete', $microsite))->toBeFalse();
});

it('performs correct ownership and role checks on ShortLinks', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole($this->superAdminRole);

    $owner = User::factory()->create();
    $owner->givePermissionTo($this->updateShortLinkPermission);
    $owner->givePermissionTo($this->deleteShortLinkPermission);

    $otherUser = User::factory()->create();
    $otherUser->givePermissionTo($this->updateShortLinkPermission);
    $otherUser->givePermissionTo($this->deleteShortLinkPermission);

    // Owned short link
    $shortLink = ShortLink::create([
        'original_url' => 'https://owner.com',
        'is_active' => true,
        'created_by' => $owner->id,
    ]);

    // Legacy short link
    $legacyShortLink = ShortLink::create([
        'original_url' => 'https://legacy.com',
        'is_active' => true,
        'created_by' => null,
    ]);

    // Owner authorization
    expect(Gate::forUser($owner)->allows('update', $shortLink))->toBeTrue();
    expect(Gate::forUser($owner)->allows('delete', $shortLink))->toBeTrue();

    // Other user authorization
    expect(Gate::forUser($otherUser)->allows('update', $shortLink))->toBeFalse();
    expect(Gate::forUser($otherUser)->allows('delete', $shortLink))->toBeFalse();

    // Legacy link authorization (non-super-admins cannot update/delete)
    expect(Gate::forUser($owner)->allows('update', $legacyShortLink))->toBeFalse();
    expect(Gate::forUser($otherUser)->allows('update', $legacyShortLink))->toBeFalse();

    // Super Admin authorization
    expect(Gate::forUser($superAdmin)->allows('update', $shortLink))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('delete', $shortLink))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('update', $legacyShortLink))->toBeTrue();
    expect(Gate::forUser($superAdmin)->allows('delete', $legacyShortLink))->toBeTrue();
});
