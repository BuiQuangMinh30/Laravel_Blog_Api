<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthorController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**********************************   Author Route Starts Here   *******************************************/
Route::get('authors','AuthorController@index')->middleware('auth:api');
Route::post('author/check/name','AuthorController@checkName');
Route::post('author/check/email','AuthorController@checkEmail');
Route::post('author/check/password','AuthorController@checkPassword');
Route::post('register',[AuthorController::class, 'register']);
Route::post('login','AuthorController@login');
Route::get('author/detail','AuthorController@getAuthor')->middleware('auth:api');
Route::post('logout','AuthorController@logout')->middleware('auth:api');
/**********************************   Author Route Ends Here   *******************************************/
