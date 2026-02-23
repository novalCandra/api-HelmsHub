<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
Route::get('/', function () {
    return view('welcome');
});

 Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
     $request->fulfill();
        return response()->json([
                'message' => 'Email berhasil diverifikasi'
        ]);
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function(Request $request) {
    $request->user()->sendEmailVerificationNotification();
        return response()->json([
            "message" => "Link deskirpisi dikirim ulang"
        ]);
});