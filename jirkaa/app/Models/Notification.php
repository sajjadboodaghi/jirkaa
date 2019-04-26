<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
    		'owner_id', 'user_id', 'type', 'link_id', 'seen'
    	];

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function link()
    {
    	return $this->belongsTo('App\Models\Link');
    }

}
