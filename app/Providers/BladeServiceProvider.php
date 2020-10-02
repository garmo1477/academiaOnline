<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Models\User;

class BladeServiceProvider extends ServiceProvider
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
        //al crear un provider hay que registrarlo en app.php de config
        \Blade::if('teacher', function(){
            if(auth()->check()){
                return auth()->user()->isTeacher();
            }
            return false;
        });
    }
}
