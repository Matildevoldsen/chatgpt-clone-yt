<?php

use App\Livewire\Pages\Chat;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/chat', function () {
        $chat = auth()->user()->conversations()->create([]);

        return redirect()->route('chat.show', $chat);
    })->name('chat');
    Route::get('/chat/{conversation:uuid}', Chat::class)->name('chat.show');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
