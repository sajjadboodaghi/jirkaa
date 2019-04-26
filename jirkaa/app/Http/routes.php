<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'guest'], function() {
	Route::get('password/reset', function() {
		return view('auth.email');
	});

	Route::post('password/email', 'Auth\PasswordController@postEmail');

	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');

	Route::post('password/reset', 'Auth\PasswordController@postReset');

	Route::get('/', 'GuestController@getWelcome');
	
	Route::controller('guest', 'GuestController');
});

Route::group(['middleware' => 'auth'], function() {
	Route::get('logout', 'Auth\AuthController@getLogout');

	Route::controller('user', 'UserController');
	Route::controller('link', 'LinkController');
	Route::controller('daily', 'DailyController');
	Route::controller('search', 'SearchController');
	Route::controller('hot', 'HotController');
	Route::controller('bookmark', 'BookmarkController');
	Route::controller('tag', 'TagController');
	Route::controller('report', 'ReportController');

});

