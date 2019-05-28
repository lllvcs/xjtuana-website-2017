<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
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

    public function update(User $user, Shop $shop)
    {
        return $shop->deleted_at === null
        && $user->isMember()
            && (
            $user->member->department_id === 1
            || $user->member->department_id === 2
            || $user->member->id == $shop->own_id
        );
    }

    public function view(User $user)
    {
        return $user->isMember();
    }

    public function search(User $user)
    {
        return $user->isMember();
    }

    public function buy(User $user, Shop $shop)
    {
        return $shop->deleted_at === null
        && $user->isMember() && (($shop->number - $shop->sold) >= 1);
    }

    public function delete(User $user, Shop $shop)
    {
        return $shop->deleted_at === null
        && $user->isMember()
            && (
            $user->member->department_id === 1
            || $user->member->department_id === 2
            || $user->member->id == $shop->own_id
        );
    }

    public function create(User $user)
    {
        return $user->isMember();
    }

}
