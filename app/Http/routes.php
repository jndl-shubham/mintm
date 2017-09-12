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
use Illuminate\Support\Facades\Input;
use App\User;
use App\Token;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\Request;
use App\Client;
Route::get('/', function () {
    return view('welcome');
});

// route to get the user details.
Route::get('/userDetails', 'HomeController@userDetails');

//route to register a new user
Route::post('/signup', 'HomeController@signup');

//route to signin a new user
Route::post('/signin', 'HomeController@signin');

//route to access dashboard
Route::get('/dashboard', 'HomeController@dashboard');