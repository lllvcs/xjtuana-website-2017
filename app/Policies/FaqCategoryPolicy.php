<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FaqCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqCategoryPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can search faq categories.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function search(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the faq category.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\FaqCategory  $faq_category
     * @return mixed
     */
    public function view(User $user, FaqCategory $faq_category)
    {
        return true;
    }

    /**
     * Determine whether the user can create faq categories.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can update the faq category.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\FaqCategory  $faq_category
     * @return mixed
     */
    public function update(User $user, FaqCategory $faq_category)
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can delete the faq category.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\FaqCategory  $faq_category
     * @return mixed
     */
    public function delete(User $user, FaqCategory $faq_category)
    {
        return $user->isAdmin() && $faq_category->faqs->isEmpty();
    }

}
