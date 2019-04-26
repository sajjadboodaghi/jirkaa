<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Link;
use App\Models\Tag;
use App\Models\Bookmark;
use Auth;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function getCreate()
    {
        $tags = Tag::all();
        return view('link.create', ['user' => $this->user, 'tags' => $tags, 'notificationsCount' => $this->user->notificationsCount()]);
    }
    public function postStore(Request $request)
    {

    	$this->validate($request, [
    		'title' => 'required|min:3|max:255',
    		'url' => 'required|max:500|url'
    		],[
                'title.required' => 'عنوان پیوند را درج کنید!',
                'title.min' => 'طول عنوان حداقل باید ۳ حرف باشد!',
                'title.max' => 'طول عنوان حداکثر میتواند ۲۵۵ حرف باشد!',
                'url.required' => 'نشانی پیوند را درج کنید!',
                'url.max' => 'طول نشانی پیوند حداکثر میتواند ۵۰۰ کاراکتر باشد!',
                'url.url' => 'پیوند ارسال شده معتبر نیست.<br>توجه داشته باشید که پیوند حتما باید با //:http شروع شود!'
            ]);

        $link = Link::where('url', $request['url']);

        // if the link was inserted before
        if($link->count()) {
            $link = $link->first();

            // if current user was owner of the link
            if($link->user_id == $this->user->id) {
                return response('exists', '422');
            } else {

                $bookmarkcount = Bookmark::where(['link_id' => $link->id, 'user_id' => $this->user->id])->count();
                // if current user hadn't bookmarked the link before
                if(!$bookmarkcount) {
                    Bookmark::create([
                        'link_id' => $link->id,
                        'user_id' => $this->user->id,
                        ]);
                    return response('bookmarked', 422);
                } 

                return response('alreadybookmarked', 422);

            }
        }

    	$link = Link::create([
    		'title' => $request['title'],
    		'url' => $request['url'],
    		'user_id' => $this->user->id
    		]);

        $tagIds = $request['tags'];
        $tags = [];
        if(count($tagIds) <= 2 && count($tagIds) > 0) {
            foreach($tagIds as $tagid) {
                if(!is_numeric($tagid)) {
                    if(strlen($tagid) <= 40) {
                        $newtag = new Tag;
                        $newtag->title = $tagid;
                        $newtag->save();
                        $tags[] = ['tag_id' => $newtag->id];
                    }
                } else {
                    $tags[] = ['tag_id' => $tagid];
                }
            }
                  
            $link->tags()->attach($tags);
        }

    	return response('Added', 200);
    }

    public function postDelete($id)
    {
        if($this->user->isOwnerOf($id)) {   
            $link = $this->user->links()->find($id);
            if($link) {
                $link->delete();
                return response('OK', 200);
            } else {
                return response('This link not found!', '503');
            }
        } else {
            return response('You don\'t have permission for update this link', 503);
        }
    }

    public function getEdit($id)
    {
        if($this->user->isOwnerOf($id)) {
            $link = $this->user->links()->findOrFail($id);
            $tags = Tag::all();
            $oldtags = json_encode(array_values(array_pluck($link->tags->toArray(), 'id')));
            return view('link.edit', ['link' => $link, 'user' => $this->user, 'tags' => $tags, 'oldtags' => $oldtags, 'notificationsCount' => $this->user->notificationsCount()]);
        } else {
            Auth::logout();
            abort(404);
        }
    }

    public function postUpdate($id, Request $request)
    {
        if($this->user->isOwnerOf($id)) {
            $link = $this->user->links->find($id);
            $this->validate($request, [
            'title' => 'required|min:3|max:255',
            'url' => 'required|max:500|url'
            ],[
                'title.required' => 'عنوان پیوند را درج کنید!',
                'title.min' => 'طول عنوان حداقل باید ۳ حرف باشد!',
                'title.max' => 'طول عنوان حداکثر میتواند ۲۵۵ حرف باشد!',
                'url.required' => 'نشانی پیوند را درج کنید!',
                'url.max' => 'طول نشانی پیوند حداکثر میتواند ۵۰۰ کاراکتر باشد!',
                'url.url' => 'پیوند ارسال شده معتبر نیست.<br>توجه داشته باشید که پیوند حتما باید با //:http شروع شود!'
            ]);

            $existsUrl = Link::where('url', $request['url'])->where('id', '<>', $id);
            if($existsUrl->count() > 0 && $existsUrl->first()->id != $id) {
                return response('exists', 422);
            }

            $link->title = $request['title'];
            $link->url = $request['url'];
            $link->update();

            $tagIds = $request['tags'];
            $tags = array();
            if(count($tagIds) > 2) {
                return response('OK', 200);
            } 
            if(count($tagIds) > 0) {
                foreach($tagIds as $tagid) {
                    if(!is_numeric($tagid)) {
                        if(strlen($tagid) <= 40) {
                            $newtag = new Tag;
                            $newtag->title = $tagid;
                            $newtag->save();
                            $tags[] = $newtag->id;
                        }
                    } else {
                        $tags[] = $tagid;
                    }
                }
            }
            $link->tags()->sync($tags);
            

            return response('OK', 200);
        } else {
            return response('You don\'t have permission for update this link', 503);
        }
    }

    public function getLinktime($link_id)
    {
        return Link::find($link_id)->created_at->diffForHumans();
    }

}
