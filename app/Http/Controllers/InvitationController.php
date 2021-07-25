<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\ShoppingListUser;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $invitations = ShoppingListUser::whereHas("user", function($q){
            $q->where([
                ['id', '=', Auth::id()],
                ['status', 0]   //invitation status
            ]);
        })->get();
        return view('invitations.index', ['invitations' => $invitations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function list($uid){
        // return ShoppingList::whereHas('user', function($q) use($uid){
        //     $q->where([
        //         ['id', '=', $uid],
        //         ['status', '=', 0]
        //     ]);
        // })->get()->toJson();
        return ShoppingListUser::with('user:id,name', 'shoppinglist:id,name', 'inviter:id,name')
            ->where([
                ['user_id', $uid],
                ['status', 0]
                ])
            ->get()
            ->toJson();
    }

    public function accept(int $list, int $uid){
        $inv = ShoppingListUser::where([
            ['shoppinglist_id', $list],
            ['user_id', $uid],
            ['status', 0]
        ])->firstOrFail();

        $inv->status = 1;

        $inv->save();

        return redirect('/list/'.$list);
    }

    public function decline(int $list, int $uid){
        $inv = ShoppingListUser::where([
            ['shoppinglist_id', $list],
            ['user_id', $uid],
            ['status', 0]
        ])->firstOrFail();

        $inv->delete();

        return redirect('/invitations');
    }
}
