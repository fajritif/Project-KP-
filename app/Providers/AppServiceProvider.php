<?php

namespace App\Providers;

use App\Models\Company;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.app', function($view){
            $companies = Company::with('pks')->orderBy('NAMA');
            $companies = $companies->get();

            $companies = $companies->filter(function($company){
                return $company->pks->count()>0;
            });
            $view->with('companies',$companies);
        });
    }
}
