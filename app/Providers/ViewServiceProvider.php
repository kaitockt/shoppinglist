<?php

namespace App\Providers;

use App\Models\ShoppingListUser;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;

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
        //
        // view()->composer('invitations.dropdown',function($view){
        //     $uid = Auth::id();
        //     $view->with(
        //         'invitations', [1, 2]
        //     );
        // });
        view()->composer('invitations.dropdown',function($view){
            $uid = Auth::id();
            $view->with(
                'invitations', ShoppingListUser::with('user:id,name', 'shoppinglist:id,name', 'inviter:id,name')
                ->where([
                    ['user_id', $uid],
                    ['status', 0]
                    ])
                ->take(10)  //limit by 10
                ->get()
                ->toArray()
            );
        });
    }
}
