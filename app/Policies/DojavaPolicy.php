<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Dojava;
use Illuminate\Auth\Access\HandlesAuthorization;

class DojavaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Dojava');
    }

    public function view(AuthUser $authUser, Dojava $dojava): bool
    {
        return $authUser->can('View:Dojava');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Dojava');
    }

    public function update(AuthUser $authUser, Dojava $dojava): bool
    {
        return $authUser->can('Update:Dojava');
    }

    public function delete(AuthUser $authUser, Dojava $dojava): bool
    {
        return $authUser->can('Delete:Dojava');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Dojava');
    }

    public function restore(AuthUser $authUser, Dojava $dojava): bool
    {
        return $authUser->can('Restore:Dojava');
    }

    public function forceDelete(AuthUser $authUser, Dojava $dojava): bool
    {
        return $authUser->can('ForceDelete:Dojava');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Dojava');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Dojava');
    }

    public function replicate(AuthUser $authUser, Dojava $dojava): bool
    {
        return $authUser->can('Replicate:Dojava');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Dojava');
    }

}