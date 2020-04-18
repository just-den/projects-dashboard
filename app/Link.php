<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $guarded = [];

    public function tags(){
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function category()
    {
        return $this->morphToMany('App\Category', 'categorable');
    }
}
