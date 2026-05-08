<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Vatrogasac;
use Illuminate\Auth\Access\HandlesAuthorization;

class VatrogasacPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Vatrogasac');
    }

    public function view(AuthUser $authUser, Vatrogasac $vatrogasac): bool
    {
        return $authUser->can('View:Vatrogasac');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Vatrogasac');
    }

    public function update(AuthUser $authUser, Vatrogasac $vatrogasac): bool
    {
        return $authUser->can('Update:Vatrogasac');
    }

    public function delete(AuthUser $authUser, Vatrogasac $vatrogasac): bool
    {
        return $authUser->can('Delete:Vatrogasac');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Vatrogasac');
    }

    public function restore(AuthUser $authUser, Vatrogasac $vatrogasac): bool
    {
        return $authUser->can('Restore:Vatrogasac');
    }

    public function forceDelete(AuthUser $authUser, Vatrogasac $vatrogasac): bool
    {
        return $authUser->can('ForceDelete:Vatrogasac');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Vatrogasac');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Vatrogasac');
    }

    public function replicate(AuthUser $authUser, Vatrogasac $vatrogasac): bool
    {
        return $authUser->can('Replicate:Vatrogasac');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Vatrogasac');
    }

}