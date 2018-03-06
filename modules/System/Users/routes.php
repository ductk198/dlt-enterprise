<?php

Route::get('', 'UserController@index');
Route::post('loadList', 'UserController@loadList');
Route::get('user_add', 'UserController@add');
Route::post('delete_user', 'UserController@delete');
Route::get('user_edit', 'UserController@edit');
Route::post('user_update', 'UserController@update');
Route::post('user_GetDepartment', 'UserController@GetDepartment');
Route::get('search', 'UserController@search');
Route::post('import', 'UserController@import');
Route::post('saveimport', 'UserController@saveimport');
// Zend tree
Route::get('zendtree/getunit', 'TreeController@getunit');
Route::get('zendtree/getchildunit', 'TreeController@getchildunit');
Route::get('zendtree/zendUser', 'TreeController@zendlist');
// phong ban
Route::get('department_add', 'DepartmentController@add');
Route::post('department_update', 'DepartmentController@update');
Route::post('department_delete', 'DepartmentController@delete');
Route::get('department_edit', 'DepartmentController@edit');