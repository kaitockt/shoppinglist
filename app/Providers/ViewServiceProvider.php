<?php

namespace App\Providers;

use App\Models\ShoppingListUser;
use App\Models\ShoppingList;
use App\Models\ListItems;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use DB;

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

        // view()->composer('shoppinglist.dropdown', function($view){
        //     $uid = Auth::id();
        //     $view->with(
        //         'lists', ShoppingList::whereHas('users', function($q) use ($uid){
        //             $q->where([
        //                 ['users.id', $uid],
        //                 ['status', 1]
        //             ]);
        //         })
        //         ->take(10)
        //         ->withCount('items')
        //         ->get()
        //     ); 
        // });

        view()->composer('shoppinglist.dropdown', function($view){
            $view->with(
                'lists', DB::select(
                    'SELECT l.*, sum(if(i.valid_from <= NOW(), 1, 0)) AS validItemsCount
                    FROM shoppinglist AS l INNER JOIN shoppinglist_user AS su ON l.id = su.shoppinglist_id
                    LEFT JOIN listitems AS i ON l.id = i.list_id
                    WHERE su.user_id = 1 AND su.status = 1
                    GROUP BY su.id ORDER BY su.last_opened DESC;')
            );
        });

        // $shoppingLists = DB::select(
        //     'SELECT l.*, sum(if(i.valid_from <= NOW(), 1, 0)) AS validItemsCount
        //     FROM shoppinglist AS l INNER JOIN shoppinglist_user AS su ON l.id = su.shoppinglist_id
        //     LEFT JOIN listitems AS i ON l.id = i.list_id
        //     WHERE su.user_id = 1 AND su.status = 1
        //     GROUP BY su.id ORDER BY su.last_opened DESC;');

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
