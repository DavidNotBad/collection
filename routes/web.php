<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    dd(
//        (new \App\Collections\Test(new \App\Console\Commands\Test()))
    );
});

Route::get('/area', function () {
    new \App\Collections\Area(new \App\Console\Commands\Area(), new \App\Models\Area());
});

