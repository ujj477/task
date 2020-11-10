<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('login');
// });
// login
        Route::get('/', 'userController@login_view');
        Route::get('login', 'userController@login_view');
        Route::post('login', 'userController@login'); 
        Route::get('logout', 'userController@logout');
        Route::get('register', 'userController@add_register');
        Route::post('saveRegister', 'userController@save_register');
        Route::get('userList', 'userController@listUser');
        Route::get('ajaxListUser', 'userController@ajaxListUser');



