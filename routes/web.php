<?php

use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;


Route::get('/login', App\Livewire\Auth\Login::class)
    ->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', function () {
        auth()->logout();
        return redirect()->route('login');
    })->name('logout');
    Route::get('/', App\Livewire\Backoffice\Index::class)
        ->name('backoffice.index');

    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', App\Livewire\Backoffice\Clientes\Index::class)
            ->name('index');
        Route::get('edit/{id}', App\Livewire\Backoffice\Clientes\Edit::class)
            ->name('edit');
        Route::get('create', App\Livewire\Backoffice\Clientes\Create::class)
            ->name('create');
    });

    Route::prefix('cobrancas')->name('cobrancas.')->group(function () {
        Route::get('/', App\Livewire\Backoffice\Cobranca\Index::class)
            ->name('index');
        Route::get('edit/{id}', App\Livewire\Backoffice\Cobranca\Edit::class)
            ->name('edit');
        Route::get('create', App\Livewire\Backoffice\Cobranca\Create::class)
            ->name('create');
    });
});
