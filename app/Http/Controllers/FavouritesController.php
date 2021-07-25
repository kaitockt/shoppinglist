<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favourites;
use Illuminate\Support\Facades\Auth;

class FavouritesController extends Controller
{
    //
    public function add(string $name){
        $user = Auth::user();
        // $user = User::findOrFail($user_id);
        return $fav = $user->favourites()->create(['name' => $name])?
        redirect()->back():redirect('/home');
    }

    public function remove(string $name){
        $uid = Auth::id();
        return Favourites::where([
            ['user_id', $uid],
            ['name', $name],
        ])->delete()?redirect()->back():redirect('/home');  //TODO: Error handling
    }
}
