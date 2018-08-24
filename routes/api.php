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

Route::post('/module_reminder_assigner/{email}', [
    'as' => 'api.module_reminder_assigner',
    'uses' => 'ApiController@moduleReminderAssignerAction',
]);
