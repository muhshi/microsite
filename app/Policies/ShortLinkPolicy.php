<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShortLinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:ShortLink');
    }

    public function view(User $user, ShortLink $shortLink): bool
    {
        return $user->can('View:ShortLink');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:ShortLink');
    }

    public function update(User $user, ShortLink $shortLink): bool
    {
        return $user->isSuperAdmin() || ($user->can('Update:ShortLink') && $shortLink->created_by === $user->id);
    }

    public function delete(User $user, ShortLink $shortLink): bool
    {
        return $user->isSuperAdmin() || ($user->can('Delete:ShortLink') && $shortLink->created_by === $user->id);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('DeleteAny:ShortLink');
    }

    public function restore(User $user, ShortLink $shortLink): bool
    {
        return $user->isSuperAdmin() || ($user->can('Restore:ShortLink') && $shortLink->created_by === $user->id);
    }

    public function forceDelete(User $user, ShortLink $shortLink): bool
    {
        return $user->isSuperAdmin() || ($user->can('ForceDelete:ShortLink') && $shortLink->created_by === $user->id);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:ShortLink');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:ShortLink');
    }

    public function replicate(User $user, ShortLink $shortLink): bool
    {
        return $user->isSuperAdmin() || ($user->can('Replicate:ShortLink') && $shortLink->created_by === $user->id);
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:ShortLink');
    }
}
