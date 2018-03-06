<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/', function () {
    return redirect('system/login');
});

Route::get('admin', function () {
    return redirect('system/login');
});

Route::get('admin/login', function () {
	return redirect('system/login');
});


//Route::get('backend/login','MainCitizensController@login');


		
