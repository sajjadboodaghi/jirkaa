<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hot extends Model
{
    public function user()
    {
    	return $this->belognsTo('App\Models\User');
    }

    public function link()
    {
    	return $this->belognsTo('App\Models\Link');
    }
}
