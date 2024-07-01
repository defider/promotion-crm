<?php

use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $regions = Region::orderBy('title')->get();
    dd($regions);

    /*
    foreach (Region::all() as $region) {
         dd($region);
    }
    */

    // return view('welcome');
});
