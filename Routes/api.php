<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/blog'], function (){
    Route::get('/posts', \Modules\Blog\Http\Controllers\IndexController::class);
    Route::get('/posts/recommended/{post:slug}', \Modules\Blog\Http\Controllers\RecommendedController::class);
    Route::get('/posts/{post:slug}', \Modules\Blog\Http\Controllers\ShowController::class);
    Route::get('/posts/collection/{collection:slug}', \Modules\Blog\Http\Controllers\CollectionController::class);
});
