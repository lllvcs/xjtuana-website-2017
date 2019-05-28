<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MemberPoints;
use Illuminate\Auth\Access\HandlesAuthorization;

class PointsPolicy
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
        return $user->isManager();
    }
    
    public function editorder(User $user)
    {
        return $user->isManager();
    }

    public function view(User $user, MemberPoints $points)
    {
        return $user->isMember();
    }
    
    public function search(User $user)
    {
        return $user->isMember();
    }

    public function delete(User $user, MemberPoints $points)
    {
        return $user->isAdmin() && $user->deleted_at === null;
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function restore(User $user, MemberPoints $points)
    {
        return $user->isAdmin() && $user->deleted_at !== null;
    }
}
