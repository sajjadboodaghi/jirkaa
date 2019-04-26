<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Link;
use Auth;

// viewNeeds function is defined in main "Controller"
class SearchController extends Controller
{
	public function __construct()
	{
		$this->user = Auth::user();
	}
    public function getIndex(Request $request)
    {
        $keywords = $request['keywords'];
        $links = Link::with('user')->orderBy('id', 'desc')->paginate(config('custom.linksPerPage'))->toArray();

        $results = [];
        if(trim($keywords) != "") {
            $items = explode(' ', $keywords);
            $items = array_filter($items, function($var) {
                return trim($var) != "";
            });

            
            $results = Link::with('user')->where(function($q) use($items){
                foreach($items as $item) {
                    $q->where('title','like', "%{$item}%");
                }
            })->orderBy('id', 'desc')->paginate(config('custom.linksPerPage'))->toArray();
            return array_merge(['links' => $results, 'keywords' => $keywords, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
        } else {
            return array_merge(['links' => $links, 'keywords' => ""], $this->viewNeeds());
        }
    }

    public function getHottest()
    {
        $hottest = Link::with('user')->where('hotcount', '>', 0)->orderBy('hotcount', 'desc')->paginate(config('custom.linksPerPage'))->toArray();
        return array_merge(['links' => $hottest, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
    }    

    public function getHottest24(Link $link)
    {
        $hottest24 = $link->with('user')
            ->join('hots', 'hots.link_id', '=', 'links.id')
            ->select( 'links.id', 'links.user_id','links.title', 'links.url','links.hotcount', 'links.created_at')
            ->where('hots.created_at', '>=', date('Y-m-d').' 00:00:00')
            ->groupBy('links.id')
            ->orderBy('links.hotcount', 'desc')
            ->paginate(config('custom.linksPerPage'))->toArray();
        return array_merge(['links' => $hottest24, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
    }

    public function getBookmarks()
    {
    	$links = Link::with('user')
    		->join('bookmarks', 'bookmarks.link_id', '=', 'links.id')
    		->select('links.*')
            ->where('bookmarks.user_id', '=', $this->user->id)
    		->orderBy('bookmarks.id', 'desc')
    		->paginate(config('custom.linksPerPage'))->toArray();
        return array_merge(['links' => $links, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
    }

    public function getFriendslinks()
    {
        $links = Link::with('user')->where(function($query) {
            $query->orWhereIn('user_id', $this->user->friends()->lists('friend_id'));
        })->orderBy('id', 'desc')->paginate(config('custom.linksPerPage'))->toArray();

            
        return array_merge(['links' => $links, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
    }

    public function getLast()
    {
        $links = Link::with('user')->orderBy('id', 'desc')->paginate(config('custom.linksPerPage'))->toArray();
        return array_merge(['links' => $links, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
    }


}
