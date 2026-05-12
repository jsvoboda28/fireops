<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VatrogasnaZajednica;
use Illuminate\Auth\Access\HandlesAuthorization;

class VatrogasnaZajednicaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VatrogasnaZajednica');
    }

    public function view(AuthUser $authUser, VatrogasnaZajednica $vatrogasnaZajednica): bool
    {
        return $authUser->can('View:VatrogasnaZajednica');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VatrogasnaZajednica');
    }

    public function update(AuthUser $authUser, VatrogasnaZajednica $vatrogasnaZajednica): bool
    {
        return $authUser->can('Update:VatrogasnaZajednica');
    }

    public function delete(AuthUser $authUser, VatrogasnaZajednica $vatrogasnaZajednica): bool
    {
        return $authUser->can('Delete:VatrogasnaZajednica');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:VatrogasnaZajednica');
    }

    public function restore(AuthUser $authUser, VatrogasnaZajednica $vatrogasnaZajednica): bool
    {
        return $authUser->can('Restore:VatrogasnaZajednica');
    }

    public function forceDelete(AuthUser $authUser, VatrogasnaZajednica $vatrogasnaZajednica): bool
    {
        return $authUser->can('ForceDelete:VatrogasnaZajednica');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VatrogasnaZajednica');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VatrogasnaZajednica');
    }

    public function replicate(AuthUser $authUser, VatrogasnaZajednica $vatrogasnaZajednica): bool
    {
        return $authUser->can('Replicate:VatrogasnaZajednica');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VatrogasnaZajednica');
    }

}