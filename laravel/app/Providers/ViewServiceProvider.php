<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use RobotKudos\RKHelpers\Date as RKDate;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('users/*', function($view) {
            $view->with([
                'user'  => Auth::user()
            ]);
        });
        View::composer('*', function($view) {
            $view->with([
                'copyright_year' => RKDate::copyrightYear(2020),
            ]);
        });
    }
}
