<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Vozilo;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoziloPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Vozilo');
    }

    public function view(AuthUser $authUser, Vozilo $vozilo): bool
    {
        return $authUser->can('View:Vozilo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Vozilo');
    }

    public function update(AuthUser $authUser, Vozilo $vozilo): bool
    {
        return $authUser->can('Update:Vozilo');
    }

    public function delete(AuthUser $authUser, Vozilo $vozilo): bool
    {
        return $authUser->can('Delete:Vozilo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Vozilo');
    }

    public function restore(AuthUser $authUser, Vozilo $vozilo): bool
    {
        return $authUser->can('Restore:Vozilo');
    }

    public function forceDelete(AuthUser $authUser, Vozilo $vozilo): bool
    {
        return $authUser->can('ForceDelete:Vozilo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Vozilo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Vozilo');
    }

    public function replicate(AuthUser $authUser, Vozilo $vozilo): bool
    {
        return $authUser->can('Replicate:Vozilo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Vozilo');
    }

}