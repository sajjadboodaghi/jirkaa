<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Hot;
use App\Models\Link;
use App\Models\Notification;
use Auth;

class HotController extends Controller
{
	public function __construct()
	{
		$this->userid = Auth::id();
	}
    public function getSwitch($id)
    {
    	$link = Link::find($id);
    	if($link) {
    		$hot = Hot::where(['user_id' => $this->userid, 'link_id' => $id]);
	    	if($hot->count()) {
	    		$hot->delete();

	    		$link->hotcount -= 1;
		    	$link->update();
		    	Notification::where([
		    		'user_id' => $this->userid,
		    		'type' => 'hot',
		    		'link_id' => $id,
		    		])->delete();
	    		return response(['type' => 'unhotted', 'hotcount' => $link->hotcount], 200);
	    	} else {
	    		$hot = new Hot;
		    	$hot->user_id = $this->userid;
		    	$hot->link_id = $id;
		    	$hot->save();

		    	$link->hotcount += 1;
		    	$link->update();
		    	Notification::create([
		    		'owner_id' => $link->user_id,
		    		'user_id' => $this->userid,
		    		'type' => 'hot',
		    		'link_id' => $id,
		    		'seen' => 0
		    		]);
	    		return response(['type' => 'hotted', 'hotcount' => $link->hotcount], 200);
	    	}
	    	
    	} else {
    		return response('Error', '503');
    	}

    }
}
