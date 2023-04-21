<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('Api')->name('api.')->group(function () {



    Route::get('/ads/component', function (Request $request) {

        $ads_settings = gs()->ads_setting->adsData;

        return view('components.ads-create', ['adssettings' => $ads_settings,'type' => $request->type,'adtype' => $request->adtype,])->render();
    });

});
