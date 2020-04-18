<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function tags(){
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function category()
    {
        return $this->morphToMany('App\Category', 'categorable');
    }
    
    public function term()
    {
        return $this->morphToMany('App\Term', 'termable');
    }

}
