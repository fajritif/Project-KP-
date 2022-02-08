<?php

namespace App\Providers;

use App\Models\Company;
use Illuminate\Support\Facades\Gate;
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

            // Pengaturan gate (hak akses) ada di app/Providers/AuthServiceProvider.php
            if (! Gate::allows('view-all')) {
                $companies = $companies->where('KODE', auth()->user()->PTPN_ASAL);
            }

            $companies = $companies->get();

            $companies = $companies->filter(function($company){
                return $company->pks->count()>0;
            });
            $view->with('companies',$companies);
        });
    }
}
