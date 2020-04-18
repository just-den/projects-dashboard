<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return response()->json(Todo::all());      
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'status' => 'required',
            'content' => 'required' 
        ]);

        $todo = new Todo();
        $todo->status = $request->status;
        $todo->status_label = $request->status_label;
        $todo->content = $request->content;

        $todo->save();

        return response()->json([
            'status' => true,
            'message' => 'SAVED',
            'id' => $todo->id
        ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo, $id)
    {

        $request->validate([ 
            'id' => 'required',
            'status' => 'required',
            'content' => 'required' 
        ]);

        $to_update = $todo->findOrFail($id);
        $to_update->status = $request->status;
        $to_update->status_label = $request->status_label;
        $to_update->content = $request->content;      

        $to_update->update();

        return response()->json([
            'status' => true,
            'message' => 'UPDATED'
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo, $id)
    {
        $to_delete = $todo->findOrFail($id); 
        $to_delete->delete();

        return response()->json([
            'status' => true,
            'message' => 'DELETED'
        ]);       
    }


}
