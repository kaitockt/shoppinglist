<?php

namespace App\Providers;

use App\Models\ShoppingListUser;
use App\Models\ShoppingList;
use App\Models\ListItems;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

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

        view()->composer('shoppinglist.dropdown', function($view){
            $uid = Auth::id();
            $view->with(
                'lists', ShoppingList::whereHas('users', function($q) use ($uid){
                    $q->where([
                        ['users.id', $uid],
                        ['status', 1]
                    ]);
                })
                ->take(10)
                ->withCount('items')
                ->get()
            ); 
        });

        view()->composer('layouts.app', function() {
            // Basically do this on every refresh?
            if(Auth::check()){
                $items = ListItems::where('buy_by', '<=', Carbon::now()->format('Y-m-d'))
                    ->whereHas('list', function($q) {
                    $q->whereHas('users', function($q){
                        $uid = Auth::id();
                        $q->where('user_id', $uid);
                    });
                })->update(['priority' => 0]);
            }
        });
    }
}
