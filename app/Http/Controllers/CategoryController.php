<?php

namespace App\Http\Controllers;

use App\Category;
use App\Color;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $cats = Category::all();
        $cats = $cats->map(function($cat){
            $tags = $cat->tags->reduce(function ($acc,$tag) {
                $tag->color;
                $acc[] = collect($tag)->except(['color_id', 'category_id'])->toArray();
                return $acc;
            },[]);
            $cat = $cat->toArray();
            $cat['tags'] = $tags;
            return $cat;
        });

       return response()->json($cats);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {

        $cat = Category::find($id);
        $tags = $cat->tags->reduce(function ($acc,$tag) {
            $tag->color;
            $acc[] = collect($tag)->except(['color_id', 'category_id'])->toArray();
            return $acc;
        },[]);
        $cat = $cat->toArray();
        $cat['tags'] = $tags;

        return response()->json($cat);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $request_categories = json_decode($request->getContent());

       $response_data = [];

        foreach($request_categories as $category){

            $category_model = new Category(['name' => $category->name ]);				
            $category_model->save();  

            $_category = $category_model->toArray();

            $response_categories = [];
            $response_categories['id'] = $category_model['id'];
            $response_categories['name'] = $category_model['name'];
            $response_categories['tags'] = [];

            foreach ($category->tags as $v) {

                $color = new Color(['name' => $v->color ]);				
                $color->save();

                $_color = $color->toArray();

                $tag = new Tag(['name' => $v->name ]);
                $tag->category()->associate(Category::find($_category['id']));
                $tag->color()->associate(Color::find($_color['id']));
                
                $tag->save(); 
                
                $_tag = $tag->toArray();           

                $response_categories['tags'][] = [
                        'name' => $_tag['name'],
                        'id' => $_tag['id'],
                        'color' => [
                                'name' => $_color['name'],
                                'id' => $_color['id']
                                ]
                    ];
            }

            $response_data[] = $response_categories;

        }  

       return response()->json([
            'status' => true,
            'message' => 'SAVED',
            'body' => $response_data
        ]);
            
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, $id)
    {
        
        $request_all = collect( json_decode( $request->getContent() ) )->toArray();

        if(isset($request_all['tags'])){
            foreach ($request_all['tags'] as $k => $v) {
                $request_all['tags'][$k] = collect( $v )->toArray();
            }
        }


        $validator = Validator::make($request_all, [
            'id' => 'required',
            'name' => 'required',
            'tags.*.id' => 'required',
            'tags.*.name' => 'required',
            'tags.*.color' => 'required' 
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

        $cat = $category::findOrFail($id);

        $cat->name = $request_all['name'];
    
        $cat->save();			
                    
        foreach ($request_all['tags'] as $v) {

            $tag = Tag::findOrFail($v['id']);
            $tag->name = $v['name'];
            $tag->save();

            $color = $tag->color;
            $color->name = $v['color'];
            $color->save();           

        }

        return response()->json([
            'status' => true,
            'message' => 'UPDATED'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, $id)
    {
        $to_delete = Category::findOrFail($id); 

        $to_delete->delete();

        $to_delete->tags()->each(function($tag){
            $tag->color()->delete();
        });
    
        $to_delete->tags()->delete();

        return response()->json([
            'status' => true,
            'message' => 'DELETED'
        ]);

    }
}
