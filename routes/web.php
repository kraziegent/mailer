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

// Preview your template by visiting this URL in your browser
// http://your-app.test/mailable
// Make sure to replace 'your-app.test' with your actual app URL
Route::get('/mailable', function () {
    $user = App\Models\User::find(1);
    $recipient = App\Models\Recipient::find(1);
    $template = App\Models\MailTemplate::find(1);

    return new App\Mail\Template(
        $template,
        $user,
        $recipient
    );
});
