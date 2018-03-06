<?php
// Nhom chuc vu
Route::get('positiongroup', 'PositionGroupController@index');
Route::post('positiongroup/loadList', 'PositionGroupController@loadList');
Route::post('positiongroup/add', 'PositionGroupController@add');
Route::post('positiongroup/edit', 'PositionGroupController@edit');
Route::post('positiongroup/update', 'PositionGroupController@update');
Route::post('positiongroup/delete', 'PositionGroupController@delete');
// Nhom chuc vu
Route::get('position', 'PositionController@index');
Route::post('position/loadList', 'PositionController@loadList');
Route::post('position/add', 'PositionController@add');
Route::post('position/edit', 'PositionController@edit');
Route::post('position/update', 'PositionController@update');
Route::post('position/delete', 'PositionController@delete');