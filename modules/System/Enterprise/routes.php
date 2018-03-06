<?php

// Quan Tri Doanh Ngiep
Route::get('', 'EnterpriseController@index');
Route::post('enterprise/loadList', 'EnterpriseController@loadList');
Route::post('enterprise/add', 'EnterpriseController@add');
Route::post('enterprise/edit', 'EnterpriseController@edit');
Route::post('enterprise/update', 'EnterpriseController@update');
Route::post('enterprise/delete', 'EnterpriseController@delete');
Route::post('enterprise/import', 'EnterpriseController@import');
Route::post('enterprise/saveimport', 'EnterpriseController@saveimport');
// Nhom chuc vu
Route::get('position', 'PositionController@index');
Route::post('position/loadList', 'PositionController@loadList');
Route::post('position/add', 'PositionController@add');
Route::post('position/edit', 'PositionController@edit');
Route::post('position/update', 'PositionController@update');
Route::post('position/delete', 'PositionController@delete');
