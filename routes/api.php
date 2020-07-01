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

Route::get('/patientList', 'PatientController@index');
Route::post('/getPatient', 'PatientController@getPatient');
Route::post('/getDataPatient', 'PatientController@getDataPatient');
Route::post('/savePatient', 'PatientController@savePatient');
Route::get('/getPestool', 'Pes_tollController@index');
Route::post('/saveTriage', 'TriageController@saveTriage');
Route::post('/saveTriageHistory', 'TriageHistoryController@saveTriageHistory');
Route::post('/getlasttriage', 'TriageHistoryController@getlasttriage');
