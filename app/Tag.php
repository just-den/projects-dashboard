<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function color()
    {
        return $this->belongsTo('App\Color');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function event()
    {
        return $this->morphedByMany('App\Event', 'taggable');
    }

    public function link()
    {
        return $this->morphedByMany('App\Link', 'taggable');
    }
}
