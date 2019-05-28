<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MemberPointsRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class PointsRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user)
    {
        return $user->isAdmin();
    }
    
    public function view(User $user, MemberPointsRecord $pointsRecord)
    {
        return $user->isMember();
    }
    
    public function search(User $user)
    {
        return $user->isMember();
    }

    public function delete(User $user)
    {
        return $user->isAdmin() && $user->deleted_at === null;
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function restore(User $user, MemberPointsRecord $pointsRecord)
    {
        return $user->isAdmin() && $user->deleted_at !== null;
    }
}
