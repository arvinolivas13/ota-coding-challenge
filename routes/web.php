<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Events\MessageSent;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/posted-jobs/get-new',                 'App\Http\Controllers\PostedJobsController@getForModerator')->name('get');
Route::get('/posted-jobs/get-notification',        'App\Http\Controllers\PostedJobsController@getNotification')->name('get');
Route::get('/posted-jobs/get-by-id/{id}',          'App\Http\Controllers\PostedJobsController@getById')->name('get');
Route::put('/posted-jobs/read-notif',              'App\Http\Controllers\PostedJobsController@readNotification')->name('read.notification');
Route::get('/posted-jobs/get',                     'App\Http\Controllers\PostedJobsController@get')->name('get');

Route::resource('posted-jobs',                     'App\Http\Controllers\PostedJobsController');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/send-message', function () {
    broadcast(new MessageSent("Hello from Laravel"))->toOthers();
    return 'sent';
});

Route::get('/ping', fn () => 'pong');

require __DIR__.'/auth.php';
