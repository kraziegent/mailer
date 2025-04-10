<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('mailgun', [
        \App\Http\Controllers\Webhooks\Mailgun::class,
        'handle'
    ])->name('mailgun');
});
