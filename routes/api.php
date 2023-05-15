<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\EntrieController;
use App\Http\Controllers\PadletController;
use App\Http\Controllers\RatingController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/padlets', [PadletController::class, 'index']);
Route::get('padlets/{id}', [PadletController::class, 'findByID']);
Route::get('padlets/checkid/{id}', [PadletController::class, 'checkID']);
Route::get('padlets/search/{searchTerm}', [PadletController::class,'findBySearchTerm']);
Route::post('padlets', [PadletController::class,'save']);
Route::put('padlets/{id}', [PadletController::class,'update']);
Route::delete('/padlets/{id}', [PadletController::class, 'delete']);
Route::get('entries', [EntrieController::class,'index']);
Route::get('padlets/{id}/entries', [EntrieController::class, 'findByPadletID']);
Route::post('padlets/{id}/entries', [EntrieController::class,'save']);
Route::delete('/entries/{id}', [EntrieController::class, 'delete']);
Route::put('entries/{id}', [EntrieController::class,'update']);
Route::get('entries/{id}/ratings', [RatingController::class, 'findByEntryID']);
Route::post('entries/{id}/ratings', [RatingController::class, 'saveRating']);
Route::get('entries/{id}/comments', [CommentController::class, 'findCommentsByEntryID']);
Route::post('entries/{id}/comments', [CommentController::class, 'saveComment']);

