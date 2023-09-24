<?php

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Route::get('/','Admin\AdminController@index')->name('admin.home');
    Route::get('/','Admin\BalanceController@index')->name('admin.balance');
    Route::post('/balance','Admin\BalanceController@store')->name('admin.store');
    Route::post('/balance/filter', 'Admin\BalanceController@filter')->name('admin.filter');
});

Route::middleware(['auth'])->prefix('category')->group(function () {
    Route::get('/','Admin\CategoryController@index')->name('category.index');
    Route::get('/{id}','Admin\CategoryController@edit')->name('category.edit');
    Route::post('/','Admin\CategoryController@store')->name('category.store');
    Route::post('/update/{id}','Admin\CategoryController@update')->name('category.update');
    Route::delete('/{id}','Admin\CategoryController@destroy')->name('category.delete');
});


// Route::get('/','Site\SiteController@index')->name('home');

Auth::routes();

// Route::get('/home', function() {
//     return view('home');
// })->name('home')->middleware('auth');
