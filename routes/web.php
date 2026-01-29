<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login');

Route::view('/entrar', 'auth.login')->name('auth.login');
Route::view('/registar', 'auth.register')->name('auth.register');
Route::view('/nova-palavra-passe', 'auth.password')->name('auth.password');

Route::view('/home', 'home')->name('home');
Route::view('/minhas-receitas', 'recipes.index')->name('recipes.index');
Route::view('/receitas/criar', 'recipes.create')->name('recipes.create');
Route::view('/receitas/detalhes', 'recipes.show')->name('recipes.show');
Route::view('/pesquisa', 'search.results')->name('search.results');

Route::view('/moderador', 'moderator.home')->name('moderator.home');
Route::view('/moderador/pendentes', 'moderator.pending')->name('moderator.pending');
Route::view('/moderador/comentarios-denunciados', 'moderator.reported-comments')->name('moderator.reported-comments');
