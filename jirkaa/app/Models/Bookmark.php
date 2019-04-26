<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
	protected $fillable = ['link_id','user_id'];
	
    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function link()
    {
    	return $this->belongsTo('App\Models\Link');
    }
}
