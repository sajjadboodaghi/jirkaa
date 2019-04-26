<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Daily;

class DailyController extends Controller
{
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function getIndex()
    {
    	$cats = $this->user->dailies()->where('type', 'cat')->get();
    	return view('daily.index', ['cats' => $cats, 'user' => $this->user, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function getList()
    {
        $dailies = $this->user->dailies()->orderBy('column', 'asc')->orderBy('id', 'asc')->get();
        return view('daily.list', ['dailies' => $dailies, 'user' => $this->user, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function postStoreLink(Request $request)
    {
    	$this->validate($request, [
    		'title' => 'required',
    		'url' => 'required',
    		'column' => 'numeric',
    		'type' => 'required'
    		],[
				'title.required' => 'عنوان پیوند الزامی است!',
				'url.required' => 'نشانی پیوند الزامی است!',
    			'column.numeric' => 'ردیف حتما باید عدد(لاتین) باشد!',
    			'type.required' => 'نوع پیوند مشخص نشده است!'
    		]);

    	$daily = $this->user->dailies()->create([
    		'title' => $request['title'],
    		'url' => $request['url'],
    		'type' => $request['type'],
    		'cat_id' => $request['cat_id'],
    		'column' => $request['column']
    		]);

    	if($daily) {
    		return response('OK', 200);
    	} else {
    		return reponse('Something went wrong', 503);
    	}
    }

    public function postStoreCategory(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'column' => 'numeric'
        ], [
            'title.required' => 'عنوان دسته الزامی است!',
            'column.numeric' => 'ردیف حتما باید عدد(لاتین) باشد!'
        ]);

        $daily = $this->user->dailies()->create([
                'title' => $request['title'],
                'type' => 'cat',
                'column' => $request['column']
            ]);

        if($daily) {
            return response('OK', 200);
        } else {
            return response('Something went wrong', 503);
        }

    }

    public function postDeleteLink($id)
    {
        $result = $this->user->dailies()->find($id)->delete();
        if($result) {
            return response('OK', 200);
        }
    }
    public function postDeleteCategory($id)
    {
        $result = $this->user->dailies()->where('cat_id',$id)->orWhere('id', $id)->delete();
        if($result) {
            return response('OK', 200);
        }
    }

    public function getEditLink($id)
    {
        $daily = $this->user->dailies()->findOrFail($id);
        $cats = $this->user->dailies()->where('type', 'cat')->get();
        
        return view('daily.editlink', ['link' => $daily, 'cats' => $cats, 'user' => $this->user, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function postUpdateLink($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'url' => 'required',
            'column' => 'numeric',
            'type' => 'required'
            ],[
                'title.required' => 'عنوان پیوند الزامی است!',
                'url.required' => 'نشانی پیوند الزامی است!',
                'column.numeric' => 'ردیف حتما باید عدد(لاتین) باشد!',
                'type.required' => 'نوع پیوند مشخص نشده است!'
            ]);

        $daily = $this->user->dailies()->where('id', $id)->first();
        $daily->title = $request['title'];
        $daily->url = $request['url'];
        $daily->type = $request['type'];
        $daily->cat_id = $request['cat_id'];
        $daily->column = $request['column'];
        $daily->update();

        if($daily) {
            return response('OK', 200);
        } else {
            return reponse('Something went wrong', 503);
        }
    }

    public function getEditCategory($id)
    {
        $cat = $this->user->dailies()->findOrFail($id);
        return view('daily.editcat', ['cat' => $cat, 'user' => $this->user, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function postUpdateCategory($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'column' => 'numeric'
        ], [
            'title.required' => 'عنوان دسته الزامی است!',
            'column.numeric' => 'ردیف حتما باید عدد(لاتین) باشد!'
        ]);

        $daily = $this->user->dailies()->where('id', $id)->first();
        $daily->title = $request['title'];
        $daily->column = $request['column'];
        $daily->update();

        if($daily) {
            return response('OK', 200);
        } else {
            return reponse('Something went wrong', 503);
        }
    }

    public function getLinksOfCategory($id)
    {
        return $this->user->dailies()->where('cat_id', $id)->orderBy('column', 'asc')->orderBy('id', 'asc')->get();
    }
}
