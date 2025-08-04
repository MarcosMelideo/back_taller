<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\PaymentController;

/*car
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('client', ClientController::class);
Route::post('client/restore/{id}', 'App\Http\Controllers\ClientController@restore');
Route::post('client/deleted', 'App\Http\Controllers\ClientController@deletedClients');
Route::get('client/isDuplicated/{name}/{lastname}', 'App\Http\Controllers\ClientController@isDuplicated');
Route::get('client/hasVehicle/{id}', 'App\Http\Controllers\ClientController@hasVehicle');

Route::resource('vehicle', VehicleController::class);
Route::post('vehicle/restore/{id}', 'App\Http\Controllers\VehicleController@restore');
Route::post('vehicle/deleted', 'App\Http\Controllers\VehicleController@deletedVehicles');
Route::post('client/vehicles/{id}', 'App\Http\Controllers\VehicleController@clientVehicles');
Route::get('vehicle/payments/{vehicle_id}', 'App\Http\Controllers\PaymentController@paymentsByVehicleId');

Route::resource('diagnosis', DiagnosisController::class);
Route::post('diagnosis/restore/{id}', 'App\Http\Controllers\DiagnosisController@restore');
Route::post('diagnosis/deleted', 'App\Http\Controllers\DiagnosisController@deletedDiagnoses');
Route::post('vehicle/diagnoses/{id}', 'App\Http\Controllers\DiagnosisController@vehicleDiagnoses');

Route::resource('payment', PaymentController::class);
Route::get('payment/items/{payment_id}', 'App\Http\Controllers\PaymentController@showPaymentItems');
Route::get('payment/lastpayment/number', 'App\Http\Controllers\PaymentController@lastPaymentNumber');
Route::get('client/payments/{client_id}', 'App\Http\Controllers\PaymentController@paymentsByClientId');
Route::post('payment/restore/{id}', 'App\Http\Controllers\PaymentController@restore');
Route::post('payment/deleted', 'App\Http\Controllers\PaymentController@deletedPayments');
Route::get('payment/deleted/{id}', 'App\Http\Controllers\PaymentController@showDeleted');
Route::get('payment/items/deleted/{id}', 'App\Http\Controllers\PaymentController@showPaymentItemsDeleted');