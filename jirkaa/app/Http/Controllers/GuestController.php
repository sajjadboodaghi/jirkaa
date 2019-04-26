<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Link;
use App\Models\User;
use Session;

class GuestController extends Controller
{

    public function getWelcome()
    {
        $links = Link::with('user')->orderBy('id', 'desc')->paginate(config('custom.linksPerPage'))->toArray();
        return view('guest.welcome', ['links' => $links]);
    }

    public function getLast()
    {
        $links = Link::with('user')->orderBy('id', 'desc')->paginate(config('custom.linksPerPage'))->toArray();
        return ['links' => $links];
    }
    
    public function postRegister(Request $request)
    {
		$this->validate($request, [
            'username' => 'required|alpha_num|min:6|max:32|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'captcha' => 'required|captcha'
        ], [
            'username.required' => "وارد کردن نام کاربری الزامی است!",
            'username.alpha_num' => "نام کاربری تنها میتواند از حروف و اعداد لاتین تشکیل شده باشد!",
            'username.min' => "طول نام کاربری نباید کمتر از ۶ کاراکتر باشد!",
            'username.max' => "طول نام کاربری نباید بیشتر از ۲۴ کاراکتر باشد!",
            'username.unique' => "این نام کاربری قبلا گرفته شده است!",
            'password.required' => "وارد کردن گذرواژه الزامی است!",
            'password.min' => "طول گذرواژه نمیتواند کمتر از ۶ کاراکتر باشد!",
            'password.confirmed' => "مقدار گذرواژه با تکرار گذرواژه مطابقت ندارد!",
            'captcha.required' => 'رمز امنیتی را وارد کنید!',
            'captcha.captcha' => 'رمز امنیتی را اشتباه وارد کردید!'
        ]);

        User::create( [
        	'username' => $request['username'],
        	'password' => bcrypt($request['password'])
        	]);

        Auth::attempt(['username' => $request['username'], 'password' => $request['password']]);
        return redirect('/user/dashboard');

    }

    public function getLogin()
    {
        return redirect('/guest/welcome');
    }

    public function postLogin(Request $request)
    {
        if(Auth::attempt(['username' => $request['username'], 'password' => $request['password']], $request->has('remember'))) {
            return redirect()->intended('/user/dashboard');
        } else {
            Session::flash('error', 'کاربری با این مشخصات پیدا نشد!');
            return redirect()->back()->withInput();
        }
    }

    public function getAbout()
    {
        return view('guest.about');
    }

    public function getSearch(Request $request)
    {
        $keywords = $request['keywords'];

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
            
            return ['links' => $results, 'keywords' => $keywords];
        } else {
            return redirect('/guest/welcome');
        }

    }

    public function getLinktime($link_id)
    {
        return Link::find($link_id)->created_at->diffForHumans();
    }
}
