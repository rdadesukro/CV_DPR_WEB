<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/passwd', function () {
//     return Hash::make('123456');
// });



// Route::get('/uuid', function () {
// 	list($usec, $sec) = explode(" ", microtime());
//     $time = ((float)$usec + (float)$sec);
//     $time = str_replace(".", "-", $time);
//     $panjang = strlen($time);
//     $sisa = substr($time, -1*($panjang-5));
//     return Uuid::generate(3,rand(10,99).rand(0,9).substr($time, 0,5).'-'.rand(0,9).rand(0,9)."-".$sisa,Uuid::NS_DNS);
// });



Route::get('/login','LoginController@page_login');
Route::post('/submit-login','LoginController@submit_login');
Route::get('/logout','LoginController@logout');
Route::get('/ganti-password','LoginController@ganti_password');
Route::post('/update-password','LoginController@submit_update_password');
Route::group(["middleware"=>['auth.login','auth.menu']], function(){
	Route::get('/', 'HomeController@index');
	
	//setting-menu
	Route::group(['prefix'=>'setting-menu'], function(){
		Route::get('/', 'SettingMenuController@index');
		Route::get('/dt', 'SettingMenuController@datatable');
		Route::get('/get-data/{uuid}', 'SettingMenuController@get_data');
		Route::post('/insert', 'SettingMenuController@submit_insert');
		Route::post('/update', 'SettingMenuController@submit_update');
		Route::post('/delete', 'SettingMenuController@submit_delete');
	});

	//setting-role
	Route::group(['prefix'=>'setting-role'], function(){
		Route::get('/', 'SettingRoleController@index');
		Route::get('/dt-role', 'SettingRoleController@datatable_role');
		Route::get('/dt-menu/{id_role}', 'SettingRoleController@datatable_menu');//menu per role
		Route::get('/menu/{uuid}', 'SettingRoleController@menu_role');//tampilkan menu by role
		Route::get('/get-role/{uuid}', 'SettingRoleController@get_data_role');
		Route::get('/get-menu/{uuid}', 'SettingRoleController@get_data_menu');
		Route::post('/insert-role', 'SettingRoleController@submit_insert_role');
		Route::post('/update-role', 'SettingRoleController@submit_update_role');
		Route::post('/delete-role', 'SettingRoleController@submit_delete_role');
		Route::post('/insert-menu', 'SettingRoleController@submit_insert_menu');
		Route::post('/update-menu', 'SettingRoleController@submit_update_menu');
		Route::post('/delete-menu', 'SettingRoleController@submit_delete_menu');
	});

	   //uang jalan
	   Route::group(['prefix'=>'uang-jalan'], function(){
		Route::get('/', 'UangJalanController@index');
		Route::get('/dt/{id}', 'UangJalanController@datatable');
		// Route::get('/dt', 'UangJalanController@datatable');
		Route::get('/get-data/{uuid}', 'UangJalanController@get_data');
		Route::post('/insert_uj', 'UangJalanController@submit_insert');
		Route::post('/update', 'UangJalanController@submit_update');
		Route::post('/delete', 'UangJalanController@submit_delete');
		Route::post('/create', 'UangJalanController@create');
	});

     //setoran
	Route::group(['prefix'=>'setoran-do'], function(){
		Route::get('/', 'SetoranController@index');
		Route::get('/dt/{id}', 'SetoranController@datatable');
		Route::get('/tabel/{mobil_id}', 'SetoranController@view');
		Route::get('/get-data/{uuid}', 'SetoranController@get_data');
		Route::post('/insert', 'SetoranController@submit_insert');
		Route::post('/update', 'SetoranController@submit_update');
		Route::post('/delete', 'SetoranController@submit_delete');
	});

	     //setoran
		 Route::group(['prefix'=>'pembayaran'], function(){
			Route::get('/', 'PembayaranController@index');
			Route::get('/dt/{id}', 'PembayaranController@datatable');
			Route::get('/cetak', 'PembayaranController@cetak');
			Route::get('/get-data/{uuid}', 'PembayaranController@get_data');
			Route::get('/get-bayar/{id}', 'PembayaranController@get_bayar');
			Route::post('/insert', 'PembayaranController@submit_insert');
			Route::post('/update', 'PembayaranController@submit_update');
			Route::post('/delete', 'PembayaranController@submit_delete');
		});
	
});

