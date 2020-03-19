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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('employees/params', 'API\EmployeeController@setViewParams');
Route::apiResource('employees', 'API\EmployeeController');

Route::get('todo/step', 'API\TodoController@loadSteps');
Route::put('todo/step/active/{id}', 'API\TodoController@changeStepStatus');
Route::delete('todo/step/{id}', 'API\TodoController@deleteStep');
Route::post('todo/step/{id}', 'API\TodoController@updateStep');
Route::post('todo/step', 'API\TodoController@addStep');
Route::put('todo/active/{id}', 'API\TodoController@finishTodo');
Route::apiResource('todo', 'API\TodoController');

