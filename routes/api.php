<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
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

/**********************************   Article Route Starts Here   *******************************************/
Route::get('articles',[PostController::class, 'index']);
Route::post('article/check/title','ArticleController@checkTitle')->middleware('auth:api');
Route::post('article/check/category','ArticleController@checkCategory')->middleware('auth:api');
Route::post('article/check/body','ArticleController@checkBody')->middleware('auth:api');
Route::post('article/store',[PostController::class, 'store'])->middleware('auth:api');
Route::get('article/{id}/show',[PostController::class, 'show']);
Route::post('article/{id}/update',[PostController::class, 'update'])->middleware('auth:api');
Route::post('article/remove',[PostController::class, 'remove'])->middleware('auth:api');
Route::get('article/{keyword}/search',[PostController::class, 'searchArticle']);
Route::get('article/{id}/comments',[PostController::class, 'comments']);
/**********************************   Article Route Ends Here   *******************************************/


/**********************************   Comment Route Starts Here   *******************************************/
Route::get('comments',[CommentController::class, 'index'])->middleware('auth:api');
Route::post('comment/check/comment',[CommentController::class, 'checkComment'])->middleware('auth:api');
Route::post('comment/check/article',[CommentController::class, 'checkArticle'])->middleware('auth:api');
Route::post('comment/{id}/store',[CommentController::class, 'store'])->middleware('auth:api');
Route::get('comment/{id}/show',[CommentController::class, 'show']);
Route::post('comment/{id}/update',[CommentController::class, 'update'])->middleware('auth:api');
Route::post('comment/{id}/remove',[CommentController::class, 'remove'])->middleware('auth:api');
/**********************************   Comment Route Ends Here   *******************************************/
