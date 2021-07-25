<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\ShoppingListUser;
use App\Models\User;
use App\Models\ListItems;
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

        // $shoppingLists = ShoppingList::whereHas('user', function($q) use ($uid) {
        //     $q->where([
        //         ['id', '=', $uid],
        //         ['status', 1]   //invitation status
        //     ]);
        // })
        // ->get();

        $shoppingLists = ShoppingList::whereHas('users', function($q) use($uid){
            $q->where([
                ['id', $uid],
                ['status', 1]
                ]);
            })->withCount('items')
            ->get();

        // $invitations = ShoppingList::whereHas('user', function($q) use ($uid) {
        //     $q->where([
        //         ['id', '=', $uid],
        //         ['status', '<>', 1]   //invitation status
        //     ]);
        // })
        // ->get();

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

        if($invites){
            foreach($invites as $user){
                $users[$user['id']] = [
                    'status' => 0,
                    'inviter' => $uid
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
                    ['id', '=', $uid],
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
        dd($request);
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
        $latestList = ShoppingList::with(['users' => function($q) use ($uid){
            $q->where([
                ['id', $uid],
                ['status', '<>', 1]
            ])
            ->orderBy('last_opened', 'DESC');
        }])
        ->firstOrFail();
        return redirect('/list/'.$latestList->id);
    }

    public function detailedAdd($id){
        $list = ShoppingList::where('id', $id)
            ->whereHas('users', function($q){
                $q->where([
                    ['id', '=', Auth::id()],
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
            'priority' => ShoppingList::findOrFail($id)->items()->max('priority') + 1
        ])?
        redirect("/list/$id"):
        redirect("/list/$id");  //TODO: Error handling?
    }
}
