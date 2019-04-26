<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bookmark;
use Auth;

class BookmarkController extends Controller
{
	public function __construct()
	{
		$this->user = Auth::user();
	}
    public function getSwitch($id)
    {
    	$bookmark = $this->user->bookmarks()->where('link_id', $id);
    	if($bookmark->count()) {
    		$bookmark->delete();
    		return response(['type' => 'unbookmarked']);
    	} else {
    		$bookmark = new Bookmark;
    		$bookmark->user_id = $this->user->id;
    		$bookmark->link_id = $id;
    		$bookmark->save();
    		return response(['type' => 'bookmarked']);
    	}
    }
}
