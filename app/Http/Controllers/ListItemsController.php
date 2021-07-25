<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListItems;

class ListItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $item = ListItems::findOrFail($id);
        return view('listitems.edit', ['item' => $item]);
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
        $item = ListItems::findOrFail($id);

        $item->name = $request->input('name');
        $item->priority = $request->input('priority');
        $item->buy_by = $request->input('buy-by');
        $item->repeat = $request->input('repeat') == 'on'?
        $request->input('repeat-number')." ".$request->input('repeat-unit'):
        null;

        $item->save();

        return redirect('/list/'.$item->list_id);
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
        return ListItems::find($id)->delete()?
            redirect()->back():
            redirect('/home');  //TODO: Error handling?
    }

    public function done($id)
    {
        //hide from user but save the record
        $item = ListItems::find($id);
        $item->update(['done' => 1]);
        return redirect('/list/'.$item->list_id);
    }
}
