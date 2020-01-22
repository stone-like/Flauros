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
Route::get("/carts","CartController@getCartItems");
Route::post("/carts","CartController@addCartToList");
Route::patch("/carts","CartController@updateQuantity");
Route::delete("/carts","CartController@removeCart");
Route::delete("/clearcart","CartController@clearCart");





Route::group(["middleware" => ["role:admin|staff"]],function(){
    Route::post("/categories","CategoryController@createCategory");
    Route::patch("/categories/{id}","CategoryController@updateCategory");
    Route::delete("/categories/{id}","CategoryController@deleteCategory");

    Route::post("/products","ProductController@createProduct");
    Route::patch("/products/{id}","ProductController@updateProduct");
    Route::delete("/products/{id}","ProductController@deleteProduct");

});

//loginしているだけだとauthで制限をかければいいのかrole:userで制限をかければいいのかどっち？
Route::group(["middleware" => ["role:admin|staff|user"]],function(){
    Route::post("/addresses","AddressController@createAddress");
    Route::patch("/addresses/{id}","AddressController@updateAddress");
    Route::delete("/addresses/{id}","AddressController@deleteAddress");
    Route::post("/orders","OrderController@createOrder"); 
});


