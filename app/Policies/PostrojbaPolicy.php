<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Postrojba;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostrojbaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Postrojba');
    }

    public function view(AuthUser $authUser, Postrojba $postrojba): bool
    {
        return $authUser->can('View:Postrojba');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Postrojba');
    }

    public function update(AuthUser $authUser, Postrojba $postrojba): bool
    {
        return $authUser->can('Update:Postrojba');
    }

    public function delete(AuthUser $authUser, Postrojba $postrojba): bool
    {
        return $authUser->can('Delete:Postrojba');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Postrojba');
    }

    public function restore(AuthUser $authUser, Postrojba $postrojba): bool
    {
        return $authUser->can('Restore:Postrojba');
    }

    public function forceDelete(AuthUser $authUser, Postrojba $postrojba): bool
    {
        return $authUser->can('ForceDelete:Postrojba');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Postrojba');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Postrojba');
    }

    public function replicate(AuthUser $authUser, Postrojba $postrojba): bool
    {
        return $authUser->can('Replicate:Postrojba');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Postrojba');
    }

}