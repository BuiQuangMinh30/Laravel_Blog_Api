<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
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
Route::get('authors',[AuthorController::class, 'index'])->middleware('auth:api');
Route::post('author/check/name','AuthorController@checkName');
Route::post('author/check/email','AuthorController@checkEmail');
Route::post('author/check/password','AuthorController@checkPassword');
Route::post('register',[AuthorController::class, 'register']);
Route::post('login',[AuthorController::class, 'login']);
Route::get('author/detail','AuthorController@getAuthor')->middleware('auth:api');
Route::post('logout',[AuthorController::class, 'logout'])->middleware('auth:api');
/**********************************   Author Route Ends Here   *******************************************/


/**********************************   Category Route Starts Here   *******************************************/
Route::get('categories',[CategoryController::class, 'index']);
Route::post('category/store',[CategoryController::class, 'store'])->middleware('auth:api');
Route::get('category/{id}/show',[CategoryController::class, 'show']);
Route::post('category/{id}/update',[CategoryController::class, 'update'])->middleware('auth:api');
Route::post('category/{id}/remove',[CategoryController::class, 'remove'])->middleware('auth:api');
Route::get('category/{keyword}/search',[CategoryController::class,'searchCategory' ]);
/**********************************   Category Route Ends Here   *******************************************/
