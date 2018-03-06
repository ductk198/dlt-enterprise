<?php
// Loai danh muc
Route::get('listtype', ['as'=>'admin.listype', 'uses' =>'ListtypeController@index']);
Route::post('listtype/add', 'ListtypeController@add');
Route::post('listtype/update', 'ListtypeController@update');
Route::post('listtype/loadList', 'ListtypeController@loadList');
Route::post('listtype/delete', 'ListtypeController@delete');
Route::post('listtype/edit', 'ListtypeController@edit');
Route::post('listtype/exportcache', 'ListtypeController@exportCaches');
// Danh muc doi tuong
Route::get('list', ['as'=>'admin.list', 'uses' =>'ListController@index']);
Route::post('list/loadList', 'listController@loadList');
Route::post('list/add', 'listController@add');
Route::post('list/update', 'listController@update');
Route::post('list/delete', 'listController@delete');
Route::post('list/edit', 'listController@edit');
Route::post('list/exportCache', 'listController@exportCache');