<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\SystemSetting;

/*
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

// WhatsApp Settings API
Route::get('/whatsapp-settings', function () {
    return response()->json([
        'defaultNumber' => SystemSetting::get('whatsapp_default_number', '201044610510'),
        'enabled' => SystemSetting::get('whatsapp_enabled', '1') === '1',
        'autoOpen' => SystemSetting::get('whatsapp_auto_open', '1') === '1'
    ]);
});