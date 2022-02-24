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

        Gate::define('view-company', function (HoldingAuth $user, $company_code) {
            return 
                $user->PTPN === $company_code || 
                $user->ROLEID == 'ADMIN_HOLDING' ||
                $user->ROLEID == 'VIEWER_HOLDING';
        });   

        Gate::define('view-all', function (HoldingAuth $user) {
            return 
                $user->ROLEID == 'ADMIN_HOLDING' ||
                $user->ROLEID == 'VIEWER_HOLDING';
        });

        Gate::define('view-admin-menu', function (HoldingAuth $user) {
            return 
                $user->ROLEID == 'ADMIN_HOLDING' ||
                $user->ROLEID == 'ADMIN_ANPER' ||
                $user->ROLEID == 'ADMIN_UNIT';
        });

        Gate::define('create-device', function (HoldingAuth $user, String $company, String $pks) {
           
            $can_add_anper_device = $user->ROLEID == 'ADMIN_ANPER' && $company == $user->PTPN;
            $can_add_unit_device = $user->ROLEID == 'ADMIN_UNIT' && $company == $user->PTPN && $pks == $user->PSA;

            return $user->ROLEID == 'ADMIN_HOLDING' ||
                $can_add_anper_device ||
                $can_add_unit_device;
        });

        
        // Untuk mengatur 'siapa' boleh mengatur 'role yang mana'
        Gate::define('set-role', function (HoldingAuth $user, String $role) {
           
            $aturan = [
                'ADMIN_UNIT' => ['ADMIN_UNIT','VIEWER_UNIT'],
                'ADMIN_ANPER' => ['ADMIN_UNIT','ADMIN_ANPER','VIEWER_UNIT','VIEWER_ANPER'],
                'ADMIN_HOLDING' => ['ADMIN_UNIT','ADMIN_ANPER','ADMIN_HOLDING','VIEWER_UNIT','VIEWER_ANPER','VIEWER_HOLDING']
            ];

            return in_array($role, $aturan[$user->ROLEID]);
            
        });

        /**
         * $user    User yang sedang login
         * $role    'ADMIN_UNIT','ADMIN_ANPER','ADMIN_HOLDING','VIEWER_UNIT','VIEWER_ANPER','VIEWER_HOLDING'
         * $user_sasaran    User yang akan diupdate ROLE nya
         */
        Gate::define('update-role', function (HoldingAuth $user, String $role, $user_sasaran) {
           
            $aturan = [
                'ADMIN_UNIT' => ['ADMIN_UNIT','VIEWER_UNIT'],
                'ADMIN_ANPER' => ['ADMIN_UNIT','ADMIN_ANPER','VIEWER_UNIT','VIEWER_ANPER'],
                'ADMIN_HOLDING' => ['ADMIN_UNIT','ADMIN_ANPER','ADMIN_HOLDING','VIEWER_UNIT','VIEWER_ANPER','VIEWER_HOLDING']
            ];            
           
            $can_add_anper_device = ($user->ROLEID == 'ADMIN_ANPER') && ($user_sasaran->PTPN == $user->PTPN);
            $can_add_unit_device = ($user->ROLEID == 'ADMIN_UNIT') && ($user_sasaran->PTPN == $user->PTPN) && ($user_sasaran->PSA == $user->PSA);

            return 
                in_array($role, $aturan[$user->ROLEID]) && (
                    $user->ROLEID == 'ADMIN_HOLDING' ||
                    $can_add_anper_device ||
                    $can_add_unit_device
                );
            
        });
    }
}
