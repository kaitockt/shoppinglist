<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //

    public function findUser(int $uid, string $input){
        // return User::where([
        //     ['id', '<>', $uid],
        //     ['name', 'LIKE', $input."%"]
        // ])->get(['id', 'name'])->toJson();

        $users = User::where([
            ['id', '<>', $uid],
            ['name', 'LIKE', $input."%"]
        ])->get(['id', 'name'])->toArray();

        $res = array_map(function($user){
            return [
                'id' => $user['id'],
                'value' => $user['name']
            ];
        }, $users);

        return json_encode($res);
    }
}
