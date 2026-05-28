<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MicrositeLink;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MicrositeLinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MicrositeLink');
    }

    public function view(AuthUser $authUser, MicrositeLink $micrositeLink): bool
    {
        return $authUser->can('View:MicrositeLink');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MicrositeLink');
    }

    public function update(AuthUser $authUser, MicrositeLink $micrositeLink): bool
    {
        return $authUser->can('Update:MicrositeLink');
    }

    public function delete(AuthUser $authUser, MicrositeLink $micrositeLink): bool
    {
        return $authUser->can('Delete:MicrositeLink');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MicrositeLink');
    }

    public function restore(AuthUser $authUser, MicrositeLink $micrositeLink): bool
    {
        return $authUser->can('Restore:MicrositeLink');
    }

    public function forceDelete(AuthUser $authUser, MicrositeLink $micrositeLink): bool
    {
        return $authUser->can('ForceDelete:MicrositeLink');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MicrositeLink');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MicrositeLink');
    }

    public function replicate(AuthUser $authUser, MicrositeLink $micrositeLink): bool
    {
        return $authUser->can('Replicate:MicrositeLink');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MicrositeLink');
    }
}
