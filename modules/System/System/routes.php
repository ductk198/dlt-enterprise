<?php

// CheckAdminSystem Permision
Route::group(['middleware' => ['CheckSystemLogin']], function () {
    // exec sql
    Route::get('sql','SqlController@index');
    Route::get('sql/getall', 'SqlController@getall');
    Route::post('sql/run','SqlController@run');
    Route::post('sql/checkpass','SqlController@checkpass');
    Route::get('sql/open_script', 'SqlController@open_script');
    Route::get('sql/open_table', 'SqlController@open_table');
    Route::get('sql/new_query', 'SqlController@new_query');
    Route::post('sql/excute','SqlController@excute');
    Route::post('sql/view_excutes','SqlController@view_excutes');
    Route::post('sql/excutes','SqlController@excutes');
    Route::get('sql/zendDb','SqlController@zendDb');
    Route::post('sql/create_db','SqlController@create_db');
    Route::get('sql/restoreDb','SqlController@restoreDb');
    Route::post('sql/update_restoreDb','SqlController@update_restoreDb');
    
    // quan tri nhom quyen
    Route::get('rightgroup','RightGroupController@index');
    Route::post('rightgroup/add','RightGroupController@add');
    Route::post('rightgroup/update','RightGroupController@update');
    Route::post('rightgroup/edit','RightGroupController@edit');
    Route::post('rightgroup/delete','RightGroupController@delete');
    Route::post('rightgroup/exportworkflow','RightGroupController@exportworkflow');
    Route::post('rightgroup/saveexportworkflow','RightGroupController@saveexportworkflow');
    // Quan ly ma nguon
    Route::get('code', 'CodeController@index');
    Route::get('code/open_file', 'CodeController@open_file');
    Route::get('code/getall', 'CodeController@getall');
    Route::post('code/update_file', 'CodeController@update_file');
    Route::post('code/add_folder','CodeController@add_folder');
    Route::post('code/add_file','CodeController@add_file');
    Route::post('code/delete','CodeController@delete');
    Route::post('code/edit','CodeController@edit');
    Route::get('code/upload_file','CodeController@upload_file');
    Route::post('code/upload','CodeController@upload')->name('upload');
    Route::get('code/download','CodeController@download');
    Route::get('code/export_module','CodeController@export_module');
    Route::get('code/zend_unit','CodeController@zend_unit');
    Route::post('code/export','CodeController@export');
}); 