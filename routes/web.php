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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/changePassword','HomeController@showChangePasswordForm');
Route::post('/changePassword','HomeController@changePassword')->name('changePassword');
Route::get('/changeName','HomeController@showChangeNameForm');
Route::post('/changeName','HomeController@changeName')->name('changeName');
Route::get('/services', 'PagesController@services');


Route::resource('posts', 'PostsController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::patch('/accountPage', 'AccountController@Update')->name('Update');
Route::get('/accountPage', 'AccountController@accountPage')->name('YourAccount');
Route::post('profile', 'AccountController@update_avatar');

Route::get('/downloadPDF/{id}','PostsController@downloadPDF');


Route::prefix('admin')->group(function(){
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@Login')->name('admin.login.submit');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
});