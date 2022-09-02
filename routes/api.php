<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\VendorAuthController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\ProfileController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProductController as UserProductController;
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

Route::prefix('user')->group(function () {
    Route::post('register', [UserAuthController::class, 'registerUser']);
    Route::post('login', [UserAuthController::class, 'userLogin']);
    Route::post('otp/verify', [UserAuthController::class, 'verify']);


    Route::middleware(['auth:user'])->group(function () {

        Route::prefix('profile')->group(function () {
            Route::post('/update', [UserProfileController::class, 'getVendors']);
            Route::get('/', [UserProfileController::class, 'getProfile']);
        });

        Route::prefix('vendor')->group(function () {
            Route::post('/all', [UserController::class, 'getVendors']);
            Route::post('/products', [UserController::class, 'getVendorProducts']);

        });

        Route::prefix('cart')->group(function () {
            Route::post('/add', [UserProductController::class, 'addToCart']);
            Route::get('/{category_id}', [UserProductController::class, 'getCart']);
            Route::delete('/{cart_id}', [UserProductController::class, 'removeSingleProduct']);
            Route::delete('/all/{category_id}', [UserProductController::class, 'removeAll']);
            Route::post('/increment/{cart_id}', [UserProductController::class, 'incrementQuantity']);
            Route::post('/decrement/{cart_id}', [UserProductController::class, 'decrementQuantity']);
            Route::post('/delivery/fee', [UserProductController::class, 'setDeliveryFee']);

        });






    });

});


Route::prefix('vendor')->group(function () {
    Route::post('register', [VendorAuthController::class, 'registerVendor']);
    Route::post('login', [VendorAuthController::class, 'vendorLogin']);
    Route::get('email/verify/{id}', [VendorAuthController::class, 'verify']);

    Route::prefix('password')->group(function () {
        Route::post('reset', [VendorAuthController::class, 'resetPassword']);
        Route::post('otp', [VendorAuthController::class, 'checkForgotPasswordOtp']);
        Route::post('forgot', [VendorAuthController::class, 'forgotPassword']);
        Route::post('change', [VendorAuthController::class, 'changePassword'])->middleware('auth:vendor');
    });

    Route::middleware(['auth:vendor'])->group(function () {

        Route::prefix('product')->group(function () {
            Route::post('add', [ProductController::class, 'addProduct']);
            Route::get('/', [ProductController::class, 'getProducts']);
            Route::get('/{id}', [ProductController::class, 'getSingleProduct']);
            Route::patch('/{id}', [ProductController::class, 'updateProduct']);
            Route::delete('/{id}', [ProductController::class, 'removeProduct']);
        });

        Route::prefix('profile')->group(function () {
            Route::post('/update', [ProfileController::class, 'updateProfile']);
            Route::post('/time', [ProfileController::class, 'addWorkingDayTime']);
            Route::get('/time', [ProfileController::class, 'getWorkingDayTime']);
            Route::post('/status', [ProfileController::class, 'switchStatus']);
            Route::delete('/time/{id}', [ProfileController::class, 'removeWorkingDayTime']);
        });



        Route::post('logout', [VendorAuthController::class, 'logout']);
    });

});
