<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tag;
use Auth;

class TagController extends Controller
{
    public function __construct()
    {
        // for parent controller
        $this->user = Auth::user();
    }
    public function getLast($tag_id)
    {
    	$tag = Tag::findOrFail($tag_id);
    	$links = $tag->links()->with('user')->orderBy('id', 'desc')->paginate(config('custom.linksPerPage'))->toArray();
    	return array_merge(['links' => $links, 'tag' => $tag, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
    }

    public function getHottest($tag_id)
    {
        $tag = Tag::findOrFail($tag_id);
        $links = $tag->links()->with('user')->orderBy('hotcount', 'desc')->paginate(config('custom.linksPerPage'))->toArray();
        return array_merge(['links' => $links, 'tag' => $tag, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
    }
}
