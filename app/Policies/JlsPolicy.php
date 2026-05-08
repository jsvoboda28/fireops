<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Jls;
use Illuminate\Auth\Access\HandlesAuthorization;

class JlsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Jls');
    }

    public function view(AuthUser $authUser, Jls $jls): bool
    {
        return $authUser->can('View:Jls');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Jls');
    }

    public function update(AuthUser $authUser, Jls $jls): bool
    {
        return $authUser->can('Update:Jls');
    }

    public function delete(AuthUser $authUser, Jls $jls): bool
    {
        return $authUser->can('Delete:Jls');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Jls');
    }

    public function restore(AuthUser $authUser, Jls $jls): bool
    {
        return $authUser->can('Restore:Jls');
    }

    public function forceDelete(AuthUser $authUser, Jls $jls): bool
    {
        return $authUser->can('ForceDelete:Jls');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Jls');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Jls');
    }

    public function replicate(AuthUser $authUser, Jls $jls): bool
    {
        return $authUser->can('Replicate:Jls');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Jls');
    }

}