<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
    		'title'
    		];

   	public function links()
   	{
   		return $this->belongsToMany('App\Models\Link', 'link_tag', 'tag_id', 'link_id');
   	}

}
