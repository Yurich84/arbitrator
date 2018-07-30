<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // метатеги
        \View::share('meta',  [
                'title' => config('app.name'),
                'desc'  => config('app.name'),
                'key'   => config('app.name'),
            ]
        );

        \Blade::if('admin', function () {
            if( $user = \Auth::user() ) {
                return (bool) $user->admin;
            } else {
                return false;
            }
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
