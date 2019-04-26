<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    public function links()
    {
        return $this->hasMany('App\Models\Link');
    }
    
    public function isOwnerOf($id) {
        return (bool) $this->links()->where('id', $id)->count();
    }

    public function isAdmin()
    {
        if($this->username == config('custom.adminusername')) {
            return 1;
        } else {
            return 0;
        }
    }

    public function dailies()
    {
        return $this->hasMany('App\Models\Daily');
    }

    public function hots()
    {
        return $this->hasMany('App\Models\Hot');
    }

    public function bookmarks()
    {
        return $this->hasMany('App\Models\Bookmark');
    }

    public function friends()
    {
        return $this->belongsToMany('App\Models\User', 'friends', 'user_id', 'friend_id');
    }

    public function friendsCount()
    {
        return $this->friends()->count();
    }
    
    public function friendsOf()
    {
        return $this->belongsToMany('App\Models\User', 'friends', 'friend_id', 'user_id');
    }

    public function friendsOfCount()
    {
        return $this->friendsOf()->count();
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification', 'owner_id');
    }

    public function notificationsCount()
    {
        return $this->notifications()->where('seen', 0)->count();
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



}
