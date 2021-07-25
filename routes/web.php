<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ListItemsController;
use App\Http\Controllers\FavouritesController;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Auth::check()?redirect('/home'):view('welcome');
    // return view('welcome');
});
Auth::routes();

// Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [\App\Http\Controllers\ShoppingListController::class, 'home']);

// Route::get('/', [ShoppingListController::class, 'index']);

Route::resource('/list', ShoppingListController::class);
Route::get('list/{list}/detailedAdd', [ShoppingListController::class, 'detailedAdd'])->name('list.detailedAdd');
Route::post('list/{list}/add', [ShoppingListController::class, 'add'])->name('list.add');
Route::post('list/{list}/quickAdd', [ShoppingListController::class, 'quickAdd'])->name('list.quickAdd');
Route::resource('/invitations', InvitationController::class);

Route::resource('/listitems', ListItemsController::class);
Route::post('/listitems/{listitem}/done', [ListItemsController::class, 'done'])->name('listitems.done');

//favourite
Route::get('favourites/add/{name}', [FavouritesController::class, 'add'])->name('favourite.add');
Route::get('favourites/remove/{name}', [FavouritesController::class, 'remove'])->name('favourite.remove');