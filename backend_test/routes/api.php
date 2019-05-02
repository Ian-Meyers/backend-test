<?php

use Illuminate\Http\Request;

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

    Route::get('hello', function () {
    return 'Hello World';
});

Route::get('/books', 'BookController@returnBookList')->name('books.returnBookList');

Route::post('/books', 'BookController@createSingleBook')->name('books.createSingleBook');

Route::post('/books/{startingLocation}/{endingLocation}', 'BookController@reorder')->name('books.reorder');

Route::get('/books/{bookId}', 'BookController@returnSingleBook')->name('books.returnSingleBook');

Route::put('/books/{bookId}', 'BookController@updateSingleBook')->name('books.updateSingleBook');

Route::delete('/books/{bookId}', 'BookController@deleteSingleBook')->name('books.deleteSingleBook');

