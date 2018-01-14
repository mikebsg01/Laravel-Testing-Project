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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/feedback', function () {
    return "You've been clicked, punk.";
});

Route::group(['prefix' => 'test'], function () {
    Route::get('/email', function() {
        Mail::raw('Hello, how are my friend?', function ($message) {
            $message->subject('Hi Freddy');
            $message->to('foo@bar.com');
            $message->from('bar2@mail.com');
        });

        return 'Email was sent.';
    });
});