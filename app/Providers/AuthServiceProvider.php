<?php

namespace App\Providers;

use App\HoldingAuth;
use App\Models\Company;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('holding', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...

            return new HoldingUserProvider;
        });        

        Gate::define('view-company', function (HoldingAuth $user, Company $company) {
            return 
                $user->PTPN_ASAL === $company->KODE || 
                $user->ROLEID == 'ADMIN_HOLDING' ||
                $user->ROLEID == 'VIEWER_HOLDING';
        });   

        Gate::define('view-all', function (HoldingAuth $user) {
            return 
                $user->ROLEID == 'ADMIN_HOLDING' ||
                $user->ROLEID == 'VIEWER_HOLDING';
        });
    }
}
