<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Member;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can search members.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function search(User $user)
    {
        return $user->isMember();
    }

    /**
     * Determine whether the user can view the member.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function view(User $user, Member $member)
    {
        return $user->isMember();
    }

    /**
     * Determine whether the user can create members.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the member.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function update(User $user, Member $member)
    {
        return $user->isAdmin();
    }
    
    /**
     * Determine whether the user can update the member's netid.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function updateNetid(User $user, Member $member)
    {
        return $user->isAdmin() && $member->deleted_at === null;
    }

    /**
     * Determine whether the user can delete the member.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function delete(User $user, Member $member)
    {
        return $user->isAdmin() && $member->deleted_at === null;
    }
    
    /**
     * Determine whether the user can restore the member.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function restore(User $user, Member $member)
    {
        return $user->isAdmin() && $member->deleted_at !== null;
    }
    
    /**
     * Determine whether the user can force delete the member.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return mixed
     */
    public function forceDelete(User $user, Member $member)
    {
        return $user->isAdmin() && $member->deleted_at !== null;
    }
    
    /**
     * Determine whether the user export members.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function export(User $user)
    {
        return $user->isAdmin();
    }

}
