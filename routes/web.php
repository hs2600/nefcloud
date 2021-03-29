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
//     return view('welcome');
// });

// Route::get('/', [PagesController::class, 'index']);
Route::get('/', [\App\Http\Controllers\PagesController::class, 'index']);

// Route::view('/', 'test',['name' => 'Horacio']);
// Route::get('/about', 'PagesController@about');
// Route::get('/profile', 'PagesController@profile');

Route::get('/file', [\App\Http\Controllers\PagesController::class, 'file']);
// Route::get('/demo', 'PagesController@demo');

//sharing
Route::get('/sh/{id}', [\App\Http\Controllers\PagesController::class, 'guest_share']);

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'dashboard']);
Route::get('/dashboard/favorites/', [\App\Http\Controllers\DashboardController::class, 'favorites']);
Route::get('/dashboard/trash/', [\App\Http\Controllers\DashboardController::class, 'trash']);
Route::get('/dashboard/sharein/', [\App\Http\Controllers\DashboardController::class, 'sharein']);
Route::get('/dashboard/shareout/', [\App\Http\Controllers\DashboardController::class, 'shareout']);
Route::get('/dashboard/sharelink/', [\App\Http\Controllers\DashboardController::class, 'sharelink']);
Route::get('/dashboard/recent/', [\App\Http\Controllers\DashboardController::class, 'ajaxRecent']);
Route::get('/dashboard/tags/', [\App\Http\Controllers\DashboardController::class, 'ajaxTags']);
Route::View('/dashboard/file/', 'pages.file');

//Ajax
Route::get('/ajax/dashboard/favorites/', [\App\Http\Controllers\DashboardController::class, 'ajaxFav']);
Route::get('/ajax/dashboard/', [\App\Http\Controllers\DashboardController::class, 'ajaxHom']);
Route::get('/ajax/dashboard/trash/', [\App\Http\Controllers\DashboardController::class, 'ajaxDel']);

Route::match(array('GET', 'POST'),'/dashboard/upload/', [\App\Http\Controllers\DashboardController::class, 'upload']);
Route::post('/dashboard/ajax/flag_update/', [\App\Http\Controllers\DashboardController::class, 'flagUpdate']);

//non authenticated
Route::get('/share/{id}', [\App\Http\Controllers\GuestController::class, 'share']);
//guest upload
Route::post('/guest/upload/', [\App\Http\Controllers\GuestController::class, 'guestupload']);

//auth  //TODO: move to Laravel Authentication mechanism
Route::View('/auth', 'pages.auth');
// Route::get('/auth', [\App\Http\Controllers\SessionController::class, 'authenticate']);
Route::get('/logout', [\App\Http\Controllers\SessionController::class, 'logout']);
Route::View('/login', 'pages.login');
Route::get('/sess', [\App\Http\Controllers\SessionController::class, 'accessSessionData']);

// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
