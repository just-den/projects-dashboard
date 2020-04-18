<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Color;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = \App\Tag::all()->map(function ($tag) {
            $tag->color;
            return collect($tag)->except(['color_id', 'category_id']);
        });

        return response()->json($tags);

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
            'category_id' => 'required',
            'name' => 'required',
            'color' => 'required'
        ]);

        $color = new \App\Color([
            'name' => $request->color
        ]);
    
        $color->save();
    
        $tag = new \App\Tag([
            'name' => $request->name,
            'color_id' => $color->id,
            'category_id' => $request->category_id
        ]);
    
        $tag->save();

        return response()->json([
            'status' => true,
            'message' => 'SAVED',
            'tag_id' => $tag->id,
            'color_id' => $color->id
        ]);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag, $id)
    {
        $tag = Tag::findOrFail($id); 

        $tag->delete();

        $tag->color->delete();

        return response()->json([
            'status' => true,
            'message' => 'DELETED'
        ]);
    }

    public function getRecomendedColor(Tag $tag)
    {
        $name = $_GET['name'];

        $tag = $tag::where('name',$name)->first();

        if($tag){            
            return response()->json([
                'status' => true,
                'name' => $tag->color->name
            ]);
        }
        
    }
}
