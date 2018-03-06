<?php

// check login
Route::post('getToken', 'UserController@gettoken');
Route::post('KNTC_GuiDon', 'RecordController@GuiDon');
Route::post('KNTC_GetFile', 'RecordController@GetFile');
Route::post('KNTC_NhanDon', 'RecordController@NhanDon');
Route::post('KNTC_NhacViec', 'RecordController@NhacViec');
Route::post('KNTC_UpdateStatus', 'RecordController@UpdateStatus');