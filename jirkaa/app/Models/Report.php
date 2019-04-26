<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['link_id', 'reporter_id'];

    public function link()
    {
    	return $this->belongsTo('App\Models\Link');
    }

    public function reporter()
    {
    	return $this->belongsTo('App\Models\user', 'reporter_id');
    }
}
