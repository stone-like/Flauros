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

Route::get("/categories","CategoryController@getRootCategory");
Route::get("/categories/{id}","CategoryController@getChildCategory");

Route::group(["middleware" => ["role:admin|staff"]],function(){
    Route::post("/categories","CategoryController@createCategory");
    Route::patch("/categories/{id}","CategoryController@updateCategory");
    Route::delete("/categories/{id}","CategoryController@deleteCategory");

    Route::post("/products","ProductController@createProduct");
    Route::patch("/products/{id}","ProductController@updateProduct");
    Route::delete("/products/{id}","ProductController@deleteProduct");

});


