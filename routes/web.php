<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login');

Route::view('/entrar', 'auth.login')->name('auth.login');
Route::view('/registar', 'auth.register')->name('auth.register');
Route::view('/nova-palavra-passe', 'auth.password')->name('auth.password');
