<?php
Route::get('', 'LoginController@index');
Route::post('loadList', 'LoginController@loadList');
Route::post('checklogin', 'LoginController@checklogin');
Route::get('/logout', 'LoginController@logout');  
Route::get('/login', 'LoginController@index');    
Route::get('changepassword', 'LoginController@changePassword');  
Route::post('updateChangePassword', 'LoginController@updateChangePassword');  