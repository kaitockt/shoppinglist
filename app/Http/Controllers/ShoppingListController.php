<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\ShoppingListUser;
use App\Models\User;
use App\Models\ListItems;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;


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

        $shoppingLists = ShoppingList::whereHas('users', function($q) {
            $q -> where([
                ['user_id', Auth::id()],
                ['status', 1]
            ]);
        })
        ->get();

        $shoppingLists = DB::select(
            "SELECT l.*, sum(if(i.valid_from <= NOW() AND i.done = 0, 1, 0)) AS validItemsCount
            FROM shoppinglist AS l INNER JOIN shoppinglist_user AS su ON l.id = su.shoppinglist_id
            LEFT JOIN listitems AS i ON l.id = i.list_id
            WHERE su.user_id = $uid AND su.status = 1
            GROUP BY su.id ORDER BY su.last_opened DESC;");

        return view('shoppinglist.index', [
            'shoppingLists' => $shoppingLists,
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
            'inviter_id' => $uid
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

        if($invites){
            foreach($invites as $user){
                $users[$user['id']] = [
                    'status' => 0,
                    'inviter_id' => $uid
                ];
            }
        }
        

        $request->validate([
            'name' => 'required'
        ]);

        $list = ShoppingList::create([
            'name' => $request->input('name'),
            'created_by'=> $uid,
            'last_opened' => Carbon::now()->toDateTimeString()
        ]);


        $list->users()->attach($users);

        return redirect('/list/'.$list->id);
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
        $uid = Auth::id();
        $list = ShoppingList::where('id', $id)
            ->whereHas('users', function($q) use ($uid) {
                $q->where([
                    ['user_id', $uid],
                    ['status', 1]   //invitation status
                ]);
            })
            ->firstOrFail();    //show error message?

            //update the last opened time
            $list->users()->updateExistingPivot($uid, [
                'last_opened' => Carbon::now()
            ]);
        
        return view('shoppinglist.show', ['list' => $list]);
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
        $list = ShoppingList::findOrFail($id);
        return view('shoppinglist.edit', ['list' => $list]);
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
        // dd($request);
        $uid = Auth::id();
        $list = ShoppingList::findOrFail($id);

        $list->name = $request->input('name');

        $invites = json_decode($request->input('invite'), true);

        
        if($invites){
            //list users to be shared with
            $inviteList = array_column($invites, 'id');

            //detach existing users not on list
            $existingUsers = $list->users()->get();

            foreach($existingUsers as $existingUser){
                if(($existingUser->id != $list->creator->id) 
                && (!in_array($existingUser->id, $inviteList))){
                    $list->users()->detach($existingUser->id);
                }
            }

            //attach non-existing users to list
            $existingUsersIDs = $existingUsers->pluck('id')->toArray();

            foreach($invites as $invite){
                if(!in_array($invite['id'], array_column($existingUsersIDs, 'id'))){
                    $users[$invite['id']] = [
                        'status' => 0,
                        'inviter_id' => $uid
                    ];
                }
            }
            $list->users()->attach($users);

        } else {
            //no other user listed. Remove all other users.
            foreach($existingUsers as $existingUser){
                if($existingUser->id != $list->creator->id){
                    $list->users()->detach($existingUser->id);
                }
            }
        }

        return view('shoppinglist.show', ['list' => $list]);
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
        dd($id);
    }

    public function home() {
        $uid = Auth::id();
        $latestList = ShoppingList::whereHas('users', function($q) use ($uid){
            $q->where([
                ['user_id', $uid],
                ['status', 1]
            ])
            ->orderBy('last_opened', 'DESC');
        })
        ->firstOrFail();
        return redirect('/list/'.$latestList->id);
    }

    public function detailedAdd($id){
        $list = ShoppingList::where('id', $id)
            ->whereHas('users', function($q){
                $q->where([
                    ['user_id', '=', Auth::id()],
                    ['status', 1]   //invitation status
                ]);
            })
            ->firstOrFail();    //show error message?
        return view('shoppinglist.additem', ['list' => $list]);
    }

    public function add(Request $request, $id){
        $request->validate([
            'name' => 'required',
            'priority' => 'required|numeric'
        ]);

        return ListItems::create([
            'list_id' => $id,
            'name' => $request->input('name'),
            'priority' => $request->input('priority'),
            'buy_by' => $request->input('buy-by'),
            'repeat' => $request->input('repeat') == 'on'?
                $request->input('repeat-number')." ".$request->input('repeat-unit'):
                null,
        ])?
        redirect("/list/$id"):
        redirect("/list/$id");  //TODO: Error handling?
    }

    public function quickAdd(Request $request, $id){
        $request->validate(['name' => 'required']);
        return ListItems::create([
            'list_id' => $id,
            'name' => $request->input('name'),
            'priority' => ShoppingList::findOrFail($id)->items()->where([['done', 0], ['valid_from', '<=', date("Y-m-d")]])->max('priority') + 1
        ])?
        redirect("/list/$id"):
        redirect("/list/$id");  //TODO: Error handling?
    }
}
