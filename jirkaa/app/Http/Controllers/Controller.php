<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected function viewNeeds()
    {

        return [
	        'userHots' 		=> json_encode(array_flatten($this->user->hots()->select('link_id')->get()->toArray())),
	        'userBookmarks' => json_encode(array_flatten($this->user->bookmarks()->select('link_id')->get()->toArray())),
	        'user' 			=> $this->user
        ];
    }
}
