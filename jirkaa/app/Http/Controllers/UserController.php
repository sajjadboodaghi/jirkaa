<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Link;
use App\Models\User;
use App\Models\Tag;
use App\Models\Notification;
use Image;
use Session;

// viewNeeds function is defined in main "Controller"
class UserController extends Controller
{
    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function getDashboard()
    {
        
        $dailies = $this->user->dailies()->where(function($q) {
            $q->orWhere('type', 'link');
            $q->orWhere('type', 'cat');
        })->orderBy('column', 'asc')->orderBy('id', 'asc')->get();
        
        $tags = Tag::
                join('link_tag','tags.id', '=', 'link_tag.tag_id')
                ->select(\DB::raw('count(link_tag.id) as count,tags.*'))
                ->groupBy('tag_id')
                ->orderBy('count', 'desc')
                ->limit(130)
                ->get();
        return view('user.dashboard', array_merge(['dailies' => $dailies, 'tags' => $tags, 'user' => $this->user]));
    }

    public function getImage()
    {
        return view('user.image', ['user' => $this->user, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function postUploadimage(Request $request)
    {
        if($request->has('vertical')) {
            $vertical = $request['vertical'];
        } else {
            $vertical = "middle";
        }

        if($request->has('horizontal')) {
            $horizontal = $request['horizontal'];
        } else {
            $horizontal = "middle";
        }


        if($request->hasFile('userimage')) {
            $this->validate($request, [
                'userimage' => 'mimes:jpeg,bmp,png'
                ], [
                'userimage.mimes' => 'فقط تصاویر با پسوند jpg یا png و یا gif قابل قبول است!'
                ]);

            $image = $request->file('userimage');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $width = Image::make($image)->width();
            $height = Image::make($image)->height();
            if($width > $height) {
                switch($horizontal) {
                    case 'right':
                        $x = $width - $height;
                        $y = 0;
                    break;
                    
                    case 'middle':
                        $x = round(($width - $height) / 2);
                        $y = 0;
                    break;

                    case 'left':
                        $x = $y = 0;
                }

                Image::make($image)->crop($height,$height, $x, $y)->resize(150,150)->save('src/images/avatars/large_'.$filename);
                Image::make($image)->crop($height,$height, $x, $y)->resize(44,44)->save('src/images/avatars/small_'.$filename);
            } else {
                switch($vertical) {
                    case 'top':
                        $x = $y = 0;
                    break;
                    
                    case 'middle':
                        $x = 0;
                        $y = round(($height - $width) / 2);
                    break;

                    case 'bottom':
                        $x = 0;
                        $y = $height - $width;
                }
                
                Image::make($image)->crop($width,$width, $x, $y)->resize(150,150)->save('src/images/avatars/large_'.$filename);
                Image::make($image)->crop($width,$width, $x, $y)->resize(44,44)->save('src/images/avatars/small_'.$filename);
            }
            
            if($this->user->image != 'default.jpg') {
                if(file_exists('src/images/avatars/large_'.$this->user->image)) {
                    unlink('src/images/avatars/large_'.$this->user->image);
                }
                if(file_exists('src/images/avatars/small_'.$this->user->image)) {
                    unlink('src/images/avatars/small_'.$this->user->image);
                }
            }
            $this->user->image = $filename;
            $this->user->update();

            Session::flash('message', 'تصویر با موفقیت ذخیره شد :)');
            return redirect('/user/image');
        } else {
            return redirect('/user/image')->withErrors(['هیچ تصویری انتخاب نکردید!']);
        }
    }

    public function postDeleteimage()
    {
        if($this->user->image != 'default.jpg') {
            if(file_exists('src/images/avatars/large_'.$this->user->image)) {
                unlink('src/images/avatars/large_'.$this->user->image);
            }
            if(file_exists('src/images/avatars/small_'.$this->user->image)) {
                unlink('src/images/avatars/small_'.$this->user->image);
            }
        }

        $this->user->image = "default.jpg";
        $this->user->update();

        return response('OK', 200);
        
    }

    public function getEmail()
    {
        return view('user.email', ['user' => $this->user, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function postUpdateemail(Request $request)
    {
        $this->validate($request,[
            'password' => 'required|hash:'.$this->user->password,
            'email' => 'email',
            ], [
            'password.required' => 'وارد کردن گذرواژه الزامی است!',
            'password.hash' => 'گذرواژه را درست وارد نکردید!',
            'email.email' => 'رایانامه وارد شده معتبر نیست!',
            ]);
        $this->user->email = $request['email'];
        $this->user->update();

        if(trim($request['email']) == "") {
            Session::flash('message', 'ذخیزه شد.');
        } else {
            Session::flash('message', 'ذخیره رایانامه با موفقیت انجام شد :)');
        }
        return redirect('user/email');

    }

    public function getPassword()
    {
        return view('user.password', ['user' => $this->user, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function postUpdatepassword(Request $request)
    {
        $this->validate($request, [
            'oldpassword' => 'required|hash:'.$this->user->password,
            'newpassword' => 'required|min:6',
            'newpassword_confirmation' => 'required|same:newpassword'
            ], [
            'oldpassword.hash' => 'گذرواژه فعلی را درست وارد نکردید!',
            'oldpassword.required' => 'وارد کردن گذرواژه فعلی الزامی است!',
            'newpassword.required' => "وارد کردن گذرواژه جدید الزامی است!",
            'newpassword.min' => "طول گذرواژه نمی‌تواند کمتر از ۶ کاراکتر باشد!",
            'newpassword_confirmation.required' => "وارد کردن تکرار گذرواژه جدید الزامی است!",
            'newpassword_confirmation.same' => "مقدار گذرواژه با تکرار گذرواژه مطابقت ندارد!",
            ]);

        
        $this->user->password = bcrypt($request['newpassword']);
        $this->user->update();
        Session::flash('message', 'گذرواژه جدید با موفقیت ذخیره شد :)');
        return redirect('user/password');
    }

    public function getAbout()
    {
        return view('user.about', ['user' => $this->user, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function postUpdateabout(Request $request)
    {

        $this->validate($request, [
            'about' => 'max:200',
            ], [
            'about.max' => 'طول درباه من نمی‌تواند بیشتر از ۲۰۰ کاراکتر باشد!',
            ]);

        $this->user->about = $request['about'];
        $this->user->update();
        Session::flash('message', 'ذخیره درباره من انجام شد :)');
        return redirect('user/about');
    }

    public function getProfile($username)
    {
        $user = User::where('username', $username)->first();
        if($user) {
            $linksCount = Link::with('user')->where('user_id', $user->id)->orderBy('id', 'desc')->count();
            $followed = $this->user->friends()->where('friend_id', $user->id)->count();
            return view('user.profile', array_merge(['profile' => $user, 'linksCount' => $linksCount, 'followed' => $followed, 'user' => $this->user]));
        } else {
            abort(404);
        }
    }

    public function getProfilelinks($username)
    {
        $user = User::where('username', $username)->first();
        if($user) {
            $links = Link::with('user')->where('user_id', $user->id)->orderBy('id', 'desc')->paginate(config('custom.linksPerPage'))->toArray();
            return array_merge(['links' => $links, 'notificationsCount' => $this->user->notificationsCount()], $this->viewNeeds());
        }
    }

    public function postFollow($username)
    {
        if($this->user->username != $username) {
            $user = User::where('username', $username)->first();
            if($user) {
                $follwed = $this->user->friends()->where('friend_id', $user->id)->count();
                if($follwed) {
                    $this->user->friends()->detach($user);
                    Notification::where([
                            'owner_id' => $user->id,
                            'user_id' => $this->user->id,
                            'type' => 'follow',
                        ])->delete();
                    return ['type' => "unfollowed"];
                } else {
                    $this->user->friends()->attach($user);
                    Notification::create([
                            'owner_id' => $user->id,
                            'user_id' => $this->user->id,
                            'type' => 'follow',
                            'seen' => 0
                        ]);
                    return ['type' => "followed"];
                }

            }
        } else {
            return response('Erorr', 503);
        }
    }

    public function getFollowers()
    {
        $followers = $this->user->friendsOf()->paginate(config('custom.userviewPerPage'))->toArray();
        $friendsOfCount = $this->user->friendsOfCount();
        $followingIds = array_pluck($this->user->friends()->select('friend_id')->get()->toArray(), 'friend_id');
        return view('user.followers', ['followers' => $followers, 'user' => $this->user, 'followersCount' => $friendsOfCount, 'followingIds' => $followingIds, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function getFollowing()
    {
        $following = $this->user->friends()->paginate(config('custom.userviewPerPage'))->toArray();
        $friendsCount = $this->user->friendsCount();
        $followersIds = array_pluck($this->user->friendsOf()->select('user_id')->get()->toArray(), 'user_id');
        return view('user.following', ['following' => $following, 'user' => $this->user, 'followingCount' => $friendsCount, 'followersIds' => $followersIds, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function getBestusers()
    {
        $userIds =  Link::
            selectRaw('sum(hotcount) as hotcounts, user_id')
            ->groupBy('user_id')
            ->having('hotcounts', '>', 0)
            ->limit(10)
            ->orderBy('hotcounts', 'desc');
            
        $userIds = $userIds->lists('user_id')->toArray();

        $bestUsers = [];
        foreach($userIds as $userId) {
            $bestUsers[] = User::find($userId);
        }

        $followingIds = array_pluck($this->user->friends()->select('friend_id')->get()->toArray(), 'friend_id');
        return view('user.bestusers', ['bestusers' => $bestUsers, 'user' => $this->user, 'followingIds' => $followingIds, 'notificationsCount' => $this->user->notificationsCount()]);
    }

    public function getNotifications()
    {
        $notifications = $this->user->notifications()->with('user','link');
        $notifications->update(['seen' => 1]);
        $notifications = $notifications->orderBy('id', 'desc')->get();
        return view('user.notifications', ['user' => $this->user, 'notifications' => $notifications]);
    }

    public function getCleannotifications()
    {
        $result = $this->user->notifications()->where('seen', 1)->delete();
        if($result) {
            return response('OK', 200);
        }
    }

}
