<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Talk;
use Illuminate\Auth\Access\HandlesAuthorization;

class TalkPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Talk');
    }

    public function view(AuthUser $authUser, Talk $talk): bool
    {
        return $authUser->can('View:Talk');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Talk');
    }

    public function update(AuthUser $authUser, Talk $talk): bool
    {
        return $authUser->can('Update:Talk');
    }

    public function delete(AuthUser $authUser, Talk $talk): bool
    {
        return $authUser->can('Delete:Talk');
    }

    public function restore(AuthUser $authUser, Talk $talk): bool
    {
        return $authUser->can('Restore:Talk');
    }

    public function forceDelete(AuthUser $authUser, Talk $talk): bool
    {
        return $authUser->can('ForceDelete:Talk');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Talk');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Talk');
    }

    public function replicate(AuthUser $authUser, Talk $talk): bool
    {
        return $authUser->can('Replicate:Talk');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Talk');
    }

}