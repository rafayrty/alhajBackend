<?php
// header('Access-Control-Allow-Origin: *');  
// header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\OrdersController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::resource('users',UsersController::class);
  
    Route::post('users/update/{id}',[UsersController::class,'update']);
    Route::get('users/delete/{id}',[UsersController::class,'destroy']);
    Route::post('users/status',[UsersController::class,'status']);
    Route::post('users/fcm',[UsersController::class,'fcm']);
        Route::get('pilots',[UsersController::class,'pilots']);

    Route::resource('vehicles',VehiclesController::class);
    Route::post('vehicles/update/{id}',[VehiclesController::class,'update']);
    Route::get('vehicles/delete/{id}',[VehiclesController::class,'destroy']);


    Route::resource('orders',OrdersController::class);
    Route::get('orders/delete/{id}',[OrdersController::class,'destroy']);
    //Office Routes
    Route::get('office/orders/{type}',[OrdersController::class,'office']);
    Route::post('orders/office/save/{id}',[OrdersController::class,'officeSave']);
    Route::get('orders/office/sendToDriver/{id}',[OrdersController::class,'sendToDriver']);
//End
    Route::post('orders/update/{id}',[OrdersController::class,'update']);
    Route::get('showOrder/{id}',[OrdersController::class,'show']);
    Route::post('orders/edit/{id}',[OrdersController::class,'edit']);//Replace Dummy With THis Once Done
    Route::post('orders/dummy/{id}',[OrdersController::class,'dummy']);



    Route::get('data',[OrdersController::class,'data']);
    Route::post('orders/proceed',[OrdersController::class,'proceed']);
    Route::get('orders/collector/{id}',[OrdersController::class,'userCollector']);
    Route::get('fetchOrders/{type}',[OrdersController::class,'index']);
    Route::get('upcomingOrders',[OrdersController::class,'upcoming']);
//Collector Routes
    Route::get('collectorOrders',[OrdersController::class,'collector']);
    Route::get('ongoing/collector/',[OrdersController::class,'collectorOrders']);
//Driver Routes
Route::get('ongoing/driver',[OrdersController::class,'driver']);


    Route::post('orders/status/{id}/',[OrdersController::class,'status']);

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();


});

Route::post('token', [AuthController::class, 'requestToken']);