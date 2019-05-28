<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Building;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can search buildings.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function search(User $user)
    {
        return $user->isMember();
    }

    /**
     * Determine whether the user can view the building.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Building  $building
     * @return mixed
     */
    public function view(User $user, Building $building)
    {
        return $user->isMember();
    }

    /**
     * Determine whether the user can create buildings.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the building.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Building  $building
     * @return mixed
     */
    public function update(User $user, Building $building)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the building.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Building  $building
     * @return mixed
     */
    public function delete(User $user, Building $building)
    {
        return $user->isAdmin();
    }
    
    /**
     * Determine whether the user can search the building members.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Building  $building
     * @return mixed
     */
    public function searchMembers(User $user)
    {
        return $user->isMember();
    }
    
    /**
     * Determine whether the user can update the building members.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Building  $building
     * @return mixed
     */
    public function updateMembers(User $user, Building $building)
    {
        if($user->isManager()) {
            if($user->isAdmin()) {
                return true;
            }
            return $user->member->department_id === $building->department_id;
        }
        
        return false;
    }

}
