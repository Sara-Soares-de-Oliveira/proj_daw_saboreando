<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\RecipeController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\ModeratorController;
use App\Http\Controllers\Web\CommentController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\MetricsController;

Route::get('/', [HomeController::class, 'index'])->name('home.public');

Route::get('/entrar', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/entrar', [AuthController::class, 'login'])->name('auth.login.submit');
Route::get('/registar', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/registar', [AuthController::class, 'register'])->name('auth.register.submit');
Route::get('/nova-palavra-passe', [AuthController::class, 'showPassword'])->name('auth.password');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/minhas-receitas', [RecipeController::class, 'index'])->middleware(['web.auth', 'web.role:explorador'])->name('recipes.index');
Route::get('/receitas/criar', [RecipeController::class, 'create'])->middleware(['web.auth', 'web.role:explorador'])->name('recipes.create');
Route::post('/receitas', [RecipeController::class, 'store'])->middleware(['web.auth', 'web.role:explorador'])->name('recipes.store');
Route::get('/receitas/{recipe}/editar', [RecipeController::class, 'edit'])->middleware(['web.auth', 'web.role:explorador'])->name('recipes.edit');
Route::put('/receitas/{recipe}', [RecipeController::class, 'update'])->middleware(['web.auth', 'web.role:explorador'])->name('recipes.update');
Route::delete('/receitas/{recipe}', [RecipeController::class, 'destroy'])->middleware(['web.auth', 'web.role:explorador'])->name('recipes.destroy');
Route::get('/receitas/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/pesquisa', [SearchController::class, 'results'])->name('search.results');
Route::post('/receitas/{recipe}/comentarios', [CommentController::class, 'store'])->middleware(['web.auth', 'web.role:explorador'])->name('comments.store');
Route::post('/comentarios/{comment}/denunciar', [ReportController::class, 'store'])->middleware(['web.auth', 'web.role:explorador'])->name('reports.store');
Route::get('/metricas', [MetricsController::class, 'explorador'])->middleware(['web.auth', 'web.role:explorador'])->name('metrics.explorador');

Route::get('/moderador', [ModeratorController::class, 'home'])->middleware(['web.auth', 'web.role:moderador'])->name('moderator.home');
Route::get('/moderador/pendentes', [ModeratorController::class, 'pending'])->middleware(['web.auth', 'web.role:moderador'])->name('moderator.pending');
Route::get('/moderador/comentarios-denunciados', [ModeratorController::class, 'reportedComments'])->middleware(['web.auth', 'web.role:moderador'])->name('moderator.reported-comments');
Route::patch('/moderador/receitas/{recipe}/aprovar', [ModeratorController::class, 'approve'])->middleware(['web.auth', 'web.role:moderador'])->name('moderator.recipes.approve');
Route::patch('/moderador/receitas/{recipe}/rejeitar', [ModeratorController::class, 'reject'])->middleware(['web.auth', 'web.role:moderador'])->name('moderator.recipes.reject');
Route::patch('/moderador/comentarios/{comment}/manter', [ModeratorController::class, 'keepComment'])->middleware(['web.auth', 'web.role:moderador'])->name('moderator.comments.keep');
Route::patch('/moderador/comentarios/{comment}/remover', [ModeratorController::class, 'removeComment'])->middleware(['web.auth', 'web.role:moderador'])->name('moderator.comments.remove');
Route::get('/moderador/metricas', [MetricsController::class, 'moderador'])->middleware(['web.auth', 'web.role:moderador'])->name('metrics.moderador');
