<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Shop' => 'App\Policies\ShopPolicy',
        'App\Models\Order' => 'App\Policies\OrderPolicy',
        'App\Models\Member' => 'App\Policies\MemberPolicy',
        'App\Models\Building' => 'App\Policies\BuildingPolicy',
        'App\Models\Faq' => 'App\Policies\FaqPolicy',
        'App\Models\FaqCategory' => 'App\Policies\FaqCategoryPolicy',
        'App\Models\MemberPoints' => 'App\Policies\PointsPolicy',
        'App\Models\MemberPointsRecord' => 'App\Policies\PointsRecordPolicy',
        'App\Models\ShopList' => 'App\Policies\ShopListPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 注册CasUserProvider
        \Auth::provider('cas', function($app, array $config) {
            return new \Xjtuana\Cas\Auth\CasUserProvider($config['model']);
        });

    }
}
