<?php

namespace App\Http\Controllers;

use App\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $terms = Term::all()->sortBy('order');

       $terms = $terms->reduce(function($acc,$term){
           $acc[] = $term->toArray();
           return $acc;
       },[]);

       return response()->json($terms);
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
            'name' => 'required'
        ]);     

        $term = new Term;

        $term->name = $request->name;
        $term->order = 0;
    
        $term->save();			
        
        return response()->json([
            'status' => true,
            'message' => 'SAVED',
            'id' => $term->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term, $id)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $term = $term::findOrFail($id);

        $term->name = $request->name;
    
        $term->save();			

        return response()->json([
            'status' => true,
            'message' => 'UPDATED'
        ]);
    }


    public function updateOrder(Request $request, Term $term)
    {
        $request_all = collect( json_decode( $request->getContent() ) )->toArray();

        $terms = [];
        foreach ($request_all as $request) {
            $terms[] = collect($request)->toArray();
        }

        $validator = Validator::make($terms, [
            '*.id' => 'required|numeric',
            '*.name' => 'required',
            '*.order' => 'required|numeric',
        ]);

        if($validator->fails()){
            $messages = [];
            foreach($validator->errors()->all() as $error){
                $messages[] = $error;
            }
            return response()->json([
                'status' => false,
                'message' => $messages
            ]);
        }

        foreach ($terms as $_term) {
            $term::find($_term['id'])->update([ 'order' => $_term['order'] ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'UPDATED'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $term, $id)
    {
        $to_delete = $term::findOrFail($id); 

        $to_delete->delete();

        return response()->json([
            'status' => true,
            'message' => 'DELETED'
        ]);
    }
}
