<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/getPatient', 'PatientController@getPatient');
Route::post('/getDataPatient', 'PatientController@getDataPatient');
Route::post('/savePatient', 'PatientController@savePatient');
Route::get('/getPestool', 'Pes_tollController@index');
Route::post('/saveTriage', 'TriageController@saveTriage');
Route::post('/saveTriageHistory', 'TriageHistoryController@saveTriageHistory');
Route::post('/getlasttriage', 'TriageHistoryController@getlasttriage');

Route::prefix('paciente')->group(function () {
    Route::post('/login', 'UserAuth\PatientAuth@login');
    Route::post('/register', 'UserAuth\PatientAuth@register');
    Route::post('/checkPatient', 'UserAuth\PatientAuth@checkUser');
    Route::post('/getdataUserPatient', 'PatientController@getdataUserPatient');
    Route::put('/updateUserPatient', 'PatientController@updateUserPatient');
});

Route::prefix('staff')->group(function () {
    Route::post('/login', 'UserAuth\StaffAuth@login');
    Route::post('/register', 'UserAuth\StaffAuth@register');
});

Route::middleware('auth:staffuser')->prefix('staff')->group(function () {
    Route::post('/patientList', 'PatientController@getFilterPatient');
    Route::post('/logout', 'UserAuth\StaffAuth@logout');
    Route::post('/getUserTask', 'GiveTaskController@getUserTask');
    Route::post('/addTask', 'GiveTaskController@addTask');
});

Route::prefix('citas')->group(function () {
    Route::post('/saveCitaOnline', 'CitasController@saveCitaOnline');
    Route::post('/getCitas', 'CitasController@getCitas');
    Route::post('/getlastcita', 'CitasController@getlastcita');
});

Route::middleware('auth:staffuser')->prefix('staff')->group(function () {
    Route::get('/patientList', 'PatientController@index');
});
