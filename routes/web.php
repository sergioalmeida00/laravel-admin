<?php

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/','Admin\AdminController@index')->name('admin.home');
    Route::get('/balance','Admin\BalanceController@index')->name('admin.balance');
    Route::post('/balance','Admin\BalanceController@store')->name('admin.store');
});


Route::get('/','Site\SiteController@index')->name('home');

Auth::routes();

// Route::get('/home', function() {
//     return view('home');
// })->name('home')->middleware('auth');
