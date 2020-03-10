<?php

use App\Http\Controllers\ShopController;
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

Route::get('/', 'ShopController@index')->name('item.index');

Route::group(['middleware' => ['auth']], function() {

  Route::get('/mycart', 'ShopController@myCart')->name('mycart');
  Route::post('/mycart', 'ShopController@addMycart')->name('mycart.add');
  Route::delete('/mycart/{id}', 'ShopController@destroyCart')->name('mycart.delete');
  Route::post('/checkout', 'ShopController@checkout')->name('checkout');
});



Auth::routes();
Route::get('google/login', 'Auth\LoginController@redirectToGoogle');
Route::get('google/login/callback', 'Auth\LoginController@handleGoogleCallback');