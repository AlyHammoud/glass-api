<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\MainInfosController;
use App\Http\Controllers\MainImagesController;
use App\Http\Controllers\ProductsImagesController;
use App\Http\Controllers\ServicesImagesController;
use App\Http\Controllers\CustomersReviewsController;
use GuzzleHttp\Middleware;

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

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/user/{user}', [AuthController::class, 'update']); 
    Route::post('/logout', [AuthController::class, 'logout']);

    //upload and get images
    Route::post('/upload_main_images', [MainImagesController::class, 'store']);
    Route::get('/get_main_images/{mainInfo}', [MainImagesController::class, 'getImages']);

    Route::post('/upload_customers_reviews', [CustomersReviewsController::class, 'store']);
    Route::get('/get_customers_reviews_images/{mainInfo}', [CustomersReviewsController::class, 'getImages']);
    //end of upload and gett images

    //mainInf create new / update
    Route::post('/create/mainInfo', [MainInfosController::class, 'store']);
    Route::put('/update/mainInfo/{mainInfo}', [MainInfosController::class, 'update']);
    //end mainInfo 

    //Services and services images
    Route::post('/upload_services_images', [ServicesImagesController::class, 'store']);
    Route::get('/get_services_images/{service}', [ServicesImagesController::class, 'getImage']);

    Route::post('/create/service', [ServicesController::class, 'store']);
    Route::put('/update/service/{service}', [ServicesController::class, 'update']);

    //products resource
    Route::resource('/products', ProductsController::class);
    Route::get('/products', [ProductsController::class, 'index'])->withoutMiddleware('auth:sanctum');
    Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->withoutMiddleware('auth:sanctum');
    // Route::get('/products/{product}', [ProductsController::class, 'show'])->withoutMiddleware('auth:sanctum');
    Route::get('/all-products', [ProductsController::class, 'allProducts'])->withoutMiddleware('auth:sanctum');

    //upload products images with tinymce

    Route::post('/upload_tiny_product', [ProductsImagesController::class, 'storeEnglish']);

});




Route::get('/get-main-info', [MainInfosController::class, 'getMainInfo']);

Route::get('/all_services', [ServicesController::class, 'allServices']);
Route::get('/all_services_images', [ServicesController::class, 'allServicesImages']);
Route::get('/single_service/{serviceName}', [ServicesController::class, 'singleService']);

Route::post('/login',[AuthController::class, 'login']);
Route::post('/sendEmail',[AuthController::class, 'sendEmail']);
