<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Microsite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MicrositePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Microsite');
    }

    public function view(User $user, Microsite $microsite): bool
    {
        return $user->can('View:Microsite');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Microsite');
    }

    public function update(User $user, Microsite $microsite): bool
    {
        return $user->isSuperAdmin() || ($user->can('Update:Microsite') && $microsite->created_by === $user->id);
    }

    public function delete(User $user, Microsite $microsite): bool
    {
        return $user->isSuperAdmin() || ($user->can('Delete:Microsite') && $microsite->created_by === $user->id);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('DeleteAny:Microsite');
    }

    public function restore(User $user, Microsite $microsite): bool
    {
        return $user->isSuperAdmin() || ($user->can('Restore:Microsite') && $microsite->created_by === $user->id);
    }

    public function forceDelete(User $user, Microsite $microsite): bool
    {
        return $user->isSuperAdmin() || ($user->can('ForceDelete:Microsite') && $microsite->created_by === $user->id);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Microsite');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Microsite');
    }

    public function replicate(User $user, Microsite $microsite): bool
    {
        return $user->isSuperAdmin() || ($user->can('Replicate:Microsite') && $microsite->created_by === $user->id);
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Microsite');
    }
}
