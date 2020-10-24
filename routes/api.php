<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('create_user', 'userController@createUser');
Route::get('listuser', 'userController@listUser');
Route::post('select_business', 'userController@selectBusiness');
Route::post('create_business', 'businessController@createBusiness');
Route::get('listbusiness', 'businessController@listBusiness');
Route::post('create_products', 'productsController@createProducts');
Route::get('listproducts', 'productsController@listProducts');
Route::post('updateproducts', 'productsController@updateProducts');
Route::post('deleteproducts', 'productsController@deleteProducts');