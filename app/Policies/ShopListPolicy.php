<?php

namespace App\Policies;

use App\Models\ShopList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopListPolicy
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

    public function update(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null && 
               $user->isMember() && 
               ($user->member->department_id === 1 ||
                $user->member->department_id === 2 || 
                $user->member->id == $ShopList->purchase_id);
    }

    public function view(User $user)
    {
        return $user->isMember();
    }

    public function search(User $user)
    {
        return $user->isMember();
    }

    public function delete(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null &&
               ($ShopList->buying_state == 5 || 
                ($ShopList->buying_state == 3 && 
                $user->member->id == $ShopList->purchase_id)) && 
               $user->isMember() && 
               ($user->member->department_id === 1 || 
                $user->member->department_id === 2 ||
                ($ShopList->buying_state == 5 || 
                ($ShopList->buying_state == 3 && 
                $user->member->id == $ShopList->purchase_id)));
    }
    
    public function back(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null &&
               $ShopList->buying_state == 3 && 
               $user->member->id == $ShopList->purchase_id && 
               $user->isMember() && 
               ($user->member->department_id === 1 || 
                $user->member->department_id === 2 ||
                $user->member->id == $ShopList->purchase_id );
    }
    
    public function back_agree(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null &&
               $ShopList->buying_state == 4 && 
               $user->member->id == $ShopList->seller_id && 
               $user->isMember() && 
               ($user->member->department_id === 1 || 
                $user->member->department_id === 2 ||
                ($ShopList->buying_state == 4 && 
                $user->member->id == $ShopList->seller_id));
    }
    
    public function back_delete(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null &&
               $ShopList->buying_state == 4 && 
               ($user->member->id == $ShopList->purchase_id || 
                $user->member->id == $ShopList->seller_id) && 
               $user->isMember() && 
               ($user->member->department_id === 1 || 
                $user->member->department_id === 2 ||
                ($ShopList->buying_state == 4 && 
                ($user->member->id == $ShopList->purchase_id || 
                 $user->member->id == $ShopList->seller_id)));
    }
    
    public function buy(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null &&
               $ShopList->buying_state == 1 && 
               $user->isMember() && 
               ($user->member->department_id === 1 ||
                $user->member->department_id === 2 || 
                $user->member->id == $ShopList->purchase_id);
    }

    public function buy_delete(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null &&
               $ShopList->buying_state == 1 && 
               $user->isMember() && 
               ($user->member->department_id === 1 || 
                $user->member->department_id === 2 || 
                $user->member->id == $ShopList->purchase_id);
    }
    
    
    public function sell(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null &&
               $ShopList->buying_state == 2 && 
               $user->isMember() && 
               ($user->member->department_id === 1 || 
                $user->member->department_id === 2 ||
                $user->member->id == $ShopList->seller_id);
    }

    public function sell_delete(User $user, ShopList $ShopList)
    {
        return $ShopList->deleted_at === null &&
               $ShopList->buying_state <= 2 && 
               $user->isMember() && 
               ($user->member->department_id === 1 || 
                $user->member->department_id === 2 || 
                $user->member->id == $ShopList->seller_id);
    }
    

    public function create(User $user)
    {
        return $user->isMember();
    }

}
