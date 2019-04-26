<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
	public $timestamps = false;

	protected $fillable = ['user_id', 'type', 'title', 'url', 'column', 'cat_id'];

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }
}
