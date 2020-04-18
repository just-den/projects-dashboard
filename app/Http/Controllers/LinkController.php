<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;


class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $skip = (int)($_GET['skip']);
       
        $count = Link::count();

        $rest = $count - $skip;
        $limit = 10;
        $pagination_button = true;

         $links = Link::skip($skip)->take($limit)->get();
         
         $links->each(function($link){
            collect( $link->tags )->each(function($tag){
                $tag->color;  
            });
            $link->category;
         });				

 
         $links = $links->reduce(function ($carry, $item) {
             $temp = collect($item)->except(['created_at', 'updated_at']);
             if(isset($temp['category'][0])){
                $temp['category'] = collect($temp['category'][0])->except(['pivot']);
             }             
             $temp['tags'] = collect($item->tags)->reduce(function ($carryTags, $tag) {
                $temp2 = collect($tag)->except(['color_id', 'category_id','pivot','color.id']);
                $carryTags[] = $temp2->toArray();
                 return $carryTags;
             },[]);
             $carry[] = $temp->toArray();
             return $carry;
         },[]);
         
         if( 0 < $rest && $rest <= $limit ){
            $pagination_button = false;
         }
    
        return response()->json([
            'links' => $links,
            'pagination_button' => $pagination_button
        ]);

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
            'tags' => 'required',
            'category_id' => 'required',
            'content' => 'required' 
        ]);     

        $link = new Link;

        $link->content = $request->content;
    
        $link->save();			
                    
        $link->category()->sync($request->category_id);	
        
        $link->tags()->sync(explode(',',$request->tags));
        
        return response()->json([
            'status' => true,
            'message' => 'SAVED',
            'id' => $link->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Link $link)
    {

        $request->validate([
            'tags' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'id' => 'required' 
        ]);

        $link = Link::find($request->id);

        $link->content = $request->content;
    
        $link->save();			
                    
        $link->category()->sync($request->category_id);		
        
        $link->tags()->sync(explode(',',$request->tags));

        return response()->json([
            'status' => true,
            'message' => 'UPDATED'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link, $id)
    {
        $to_delete = Link::findOrFail($id); 

        if( $to_delete->delete() ){
    
            $to_delete->category()->detach();
            $to_delete->tags()->detach();

            return response()->json([
                'status' => true,
                'message' => 'DELETED'
            ]);
    
        }
    }

    public function getByTag(){


        $skip = (int)($_GET['skip']);
        $tag_id = (int)($_GET['tag_id']);

        $count = \App\Tag::find($tag_id)->link()->count();

        $rest = $count - $skip;
        $limit = 10;
        $pagination_button = true;

         $links= \App\Tag::find($tag_id)->link()->skip($skip)->take($limit)->get()->each(function($link){
         collect( $link->tags )->each(function($tag){
                $tag->color;  
            });
            $link->category;
         });				

         $links = $links->reduce(function ($carry, $item) {
             $temp = collect($item)->except(['created_at', 'updated_at']);
             $temp['category'] = collect($temp['category'][0])->except(['pivot']);
             $temp['tags'] = collect($item->tags)->reduce(function ($carryTags, $tag) {
                $temp2 = collect($tag)->except(['color_id', 'category_id','pivot','color.id']);
                $carryTags[] = $temp2->toArray();
                 return $carryTags;
             },[]);
             $carry[] = $temp->toArray();
             return $carry;
         },[]);
         
         if( 0 <= $rest && $rest <= $limit ){
            $pagination_button = false;
         }
    
        return response()->json([
            'links' => $links,
            'pagination_button' => $pagination_button
        ]);

    }

    public function getByCat(){


        $skip = (int)($_GET['skip']);
        $cat_id = (int)($_GET['cat_id']);

        $count = \App\Category::find($cat_id)->link()->count();

        $rest = $count - $skip;
        $limit = 10;
        $pagination_button = true;

         $links= \App\Category::find($cat_id)->link()->skip($skip)->take($limit)->get()->each(function($link){
            collect( $link->tags )->each(function($tag){
                $tag->color;  
            });
            $link->category;
         });				

         $links = $links->reduce(function ($carry, $item) {
             $temp = collect($item)->except(['created_at', 'updated_at']);
             $temp['category'] = collect($temp['category'][0])->except(['pivot']);
             $temp['tags'] = collect($item->tags)->reduce(function ($carryTags, $tag) {
                $temp2 = collect($tag)->except(['color_id', 'category_id','pivot','color.id']);
                $carryTags[] = $temp2->toArray();
                 return $carryTags;
             },[]);
             $carry[] = $temp->toArray();
             return $carry;
         },[]);
         
         if( 0 <= $rest && $rest <= $limit ){
            $pagination_button = false;
         }
    
        return response()->json([
            'links' => $links,
            'pagination_button' => $pagination_button
        ]);

    }

    public function getLinksLike()
    {

        $skip = (int)($_GET['skip']);
        $like = $_GET['like'];
       
        $count = Link::where('content', 'like', "%{$like}%")->count();

        $rest = $count - $skip;
        $limit = 10;
        $pagination_button = true;

         $links = Link::where('content', 'like', "%{$like}%")->skip($skip)->take($limit)->get();
         
         $links->each(function($link){
            collect( $link->tags )->each(function($tag){
                $tag->color;  
            });
            $link->category;
         });				

 
         $links = $links->reduce(function ($carry, $item) {
             $temp = collect($item)->except(['created_at', 'updated_at']);
             $temp['category'] = collect($temp['category'][0])->except(['pivot']);
             $temp['tags'] = collect($item->tags)->reduce(function ($carryTags, $tag) {
                $temp2 = collect($tag)->except(['color_id', 'category_id','pivot','color.id']);
                $carryTags[] = $temp2->toArray();
                 return $carryTags;
             },[]);
             $carry[] = $temp->toArray();
             return $carry;
         },[]);
         
         if( 0 < $rest && $rest <= $limit ){
            $pagination_button = false;
         }
    
        return response()->json([
            'links' => $links,
            'pagination_button' => $pagination_button
        ]);
    }
}
