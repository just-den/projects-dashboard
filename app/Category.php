<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    protected $guarded = []; 

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    public function event()
    {
        return $this->morphedByMany('App\Event', 'categorable');
    }

    public function link()
    {
        return $this->morphedByMany('App\Link', 'categorable');
    }
}
