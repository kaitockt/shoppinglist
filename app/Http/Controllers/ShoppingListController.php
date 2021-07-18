<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\ShoppingListUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ShoppingListController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //redirect to welcome if not logged in
        if (!Auth::check()) {
            return view('welcome');
        }

        $uid = Auth::id();

        //TODO: Can we combine the two query?
        $shoppingList = ShoppingList::whereHas('user', function($q) use ($uid) {
            $q->where([
                ['id', '=', $uid],
                ['status', 1]   //invitation status
            ]);
        })
        ->get();

        $invitations = ShoppingList::whereHas('user', function($q) use ($uid) {
            $q->where([
                ['id', '=', $uid],
                ['status', '<>', 1]   //invitation status
            ]);
        })
        ->get();

        

        return view('index', [
            'shoppingLists' => $shoppingList,
            'invitations' => $invitations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('shoppinglist.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return view('welcome');
        }

        $uid = Auth::id();

        $users = [$uid => [
            "status" => 1,
            'inviter' => $uid
            ]];
        $invites = json_decode($request->input('invite'), true);
        //Can't understand why array_walk did not work here, should investigate later:
        //array_walk($invites, function($user) use ($users){
            // $users[$user['id']] = ['status' => 0];
        // });

        //Here, it should work(untested)
        // array_walk($invites, function($user) use (&$users){
        //     $users[$user['id']] = ['status' => 0];
        // });

        foreach($invites as $user){
            $users[$user['id']] = [
                'status' => 0,
                'inviter' => $uid
        ];
        }

        $request->validate([
            'name' => 'required'
        ]);

        $list = ShoppingList::create([
            'name' => $request->input('name'),
            'created_by'=> $uid,
            'last_opened' => Carbon::now()->toDateTimeString()
        ]);


        $list->user()->attach($users);

        return redirect('/list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
