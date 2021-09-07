<?php

use App\Http\Controllers\PostController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'prefix' => 'post'
],function($route){
    Route::get('/', [PostController::class , 'all'])->name('post.all');
    Route::get('/{id}', [PostController::class , 'show'])->name('post.get');
    Route::post('/', [PostController::class , 'create'])->name('post.create');
    Route::post('/update/{id}', [PostController::class , 'update'])->name('post.update');
    Route::get('/delete/{id}', [PostController::class , 'delete'])->name('post.delete');
});
