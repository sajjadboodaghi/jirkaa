<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
	protected $fillable = [
        'title','url', 'user_id', 'hotcount'
    ];

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function hots()
    {
    	return $this->hasMany('App\Models\hot');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'link_tag', 'link_id', 'tag_id');
    }
}
