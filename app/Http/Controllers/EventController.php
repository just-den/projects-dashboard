<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $events= Event::all();				
         foreach ($events as $event) {
             $event->term;
             $event->category;
         }
 
         $events = $events->reduce(function ($carry, $item) {
             $temp = collect($item)->except(['created_at', 'updated_at']);
             $temp['term_id'] = isset($temp['term'][0]) ? $temp['term'][0]['id'] : 0;
             $temp['term_name'] = isset($temp['term'][0]) ? $temp['term'][0]['name'] : 0;
             unset($temp['term']);
             $temp['category_id'] = isset($temp['category'][0]) ? $temp['category'][0]['id'] : 0;
             $temp['category_name'] = isset($temp['category'][0]) ? $temp['category'][0]['name'] : 0;
             unset($temp['category']);
             $carry[] = $temp->toArray();
             return $carry;
         },[]); 
    
        return response()->json($events);
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
            'term_id' => 'required',
            'category_id' => 'required',
            'content' => 'required' 
        ]);

        $event = new Event;

        $event->content = $request->content;
    
        $event->save();			
                    
        $event->category()->sync($request->category_id);		
        
        $event->term()->sync($request->term_id);

        return response()->json([
            'status' => true,
            'message' => 'SAVED',
            'id' => $event->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {

        $request->validate([
            'term_id' => 'required',
            'category_id' => 'required',
            'status' => 'required',
            'content' => 'required',
            'id' => 'required' 
        ]);

        $event = Event::find($request->id);

        $event->content = $request->content;
        $event->status = $request->status;
    
        $event->save();			
                    
        $event->category()->sync([$request->category_id]);		
        
        $event->term()->sync([$request->term_id]);

        return response()->json([
            'status' => true,
            'message' => 'UPDATED'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event, $id)
    {
        $to_delete = Event::findOrFail($id); 

        if( $to_delete->delete() ){
    
            $to_delete->category()->detach();
            $to_delete->term()->detach();

            return response()->json([
                'status' => true,
                'message' => 'DELETED'
            ]);
    
        }
    }
}
