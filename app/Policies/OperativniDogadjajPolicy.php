<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OperativniDogadjaj;
use Illuminate\Auth\Access\HandlesAuthorization;

class OperativniDogadjajPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OperativniDogadjaj');
    }

    public function view(AuthUser $authUser, OperativniDogadjaj $operativniDogadjaj): bool
    {
        return $authUser->can('View:OperativniDogadjaj');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OperativniDogadjaj');
    }

    public function update(AuthUser $authUser, OperativniDogadjaj $operativniDogadjaj): bool
    {
        return $authUser->can('Update:OperativniDogadjaj');
    }

    public function delete(AuthUser $authUser, OperativniDogadjaj $operativniDogadjaj): bool
    {
        return $authUser->can('Delete:OperativniDogadjaj');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:OperativniDogadjaj');
    }

    public function restore(AuthUser $authUser, OperativniDogadjaj $operativniDogadjaj): bool
    {
        return $authUser->can('Restore:OperativniDogadjaj');
    }

    public function forceDelete(AuthUser $authUser, OperativniDogadjaj $operativniDogadjaj): bool
    {
        return $authUser->can('ForceDelete:OperativniDogadjaj');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OperativniDogadjaj');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OperativniDogadjaj');
    }

    public function replicate(AuthUser $authUser, OperativniDogadjaj $operativniDogadjaj): bool
    {
        return $authUser->can('Replicate:OperativniDogadjaj');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OperativniDogadjaj');
    }

}