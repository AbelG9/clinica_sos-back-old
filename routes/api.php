<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/patientList', 'PatientController@index');
Route::post('/getPatient', 'PatientController@getPatient');
Route::post('/getDataPatient', 'PatientController@getDataPatient');
Route::post('/savePatient', 'PatientController@savePatient');
Route::get('/getPestool', 'Pes_tollController@index');
Route::post('/saveTriage', 'TriageController@saveTriage');
Route::post('/saveTriageHistory', 'TriageHistoryController@saveTriageHistory');
Route::post('/getlasttriage', 'TriageHistoryController@getlasttriage');

Route::prefix('staff')->group(function () {
    Route::post('/login', 'UserAuth\StaffAuth@login');
    Route::post('/register', 'UserAuth\StaffAuth@register');
});
