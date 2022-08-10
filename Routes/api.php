<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\Api\V1\PostsController;
use Orion\Facades\Orion;

Route::group(['prefix' => '/legacy'], function () {
    Route::get('/posts', \Modules\Blog\Http\Controllers\IndexController::class);
    Route::get('/posts/recommended/{post:slug}', \Modules\Blog\Http\Controllers\RecommendedController::class);
    Route::get('/posts/{post:slug}', \Modules\Blog\Http\Controllers\ShowController::class);
    Route::get('/posts/collection/{collection:slug}', \Modules\Blog\Http\Controllers\CollectionController::class);
});

Route::group(['prefix' => '/v1'], function () {
    Orion::resource('posts', PostsController::class);
});
