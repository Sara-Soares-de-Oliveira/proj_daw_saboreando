<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MetricsController;
use App\Http\Controllers\Api\Explorer\RecipeController as ExplorerRecipeController;
use App\Http\Controllers\Api\Explorer\CommentController as ExplorerCommentController;
use App\Http\Controllers\Api\Explorer\ReportController as ExplorerReportController;
use App\Http\Controllers\Api\Moderator\RecipeController as ModeratorRecipeController;
use App\Http\Controllers\Api\Moderator\CommentController as ModeratorCommentController;
use App\Http\Controllers\Api\Moderator\ReportController as ModeratorReportController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum', 'role:explorador'])->prefix('explorador')->group(function () {
    Route::get('/recipes', [ExplorerRecipeController::class, 'index']);
    Route::post('/recipes', [ExplorerRecipeController::class, 'store']);
    Route::put('/recipes/{recipe}', [ExplorerRecipeController::class, 'update']);
    Route::delete('/recipes/{recipe}', [ExplorerRecipeController::class, 'destroy']);
    Route::post('/recipes/{recipe}/comments', [ExplorerCommentController::class, 'store']);
    Route::post('/comments/{comment}/reports', [ExplorerReportController::class, 'store']);
});

Route::get('/recipes', [\App\Http\Controllers\Api\PublicRecipeController::class, 'index']);
Route::get('/recipes/{recipe}', [\App\Http\Controllers\Api\PublicRecipeController::class, 'show']);
Route::get('/recipes/{recipe}/comments', [\App\Http\Controllers\Api\PublicCommentController::class, 'index']);

Route::middleware(['auth:sanctum', 'role:moderador'])->prefix('moderador')->group(function () {
    Route::get('/recipes', [ModeratorRecipeController::class, 'index']);
    Route::patch('/recipes/{recipe}/approve', [ModeratorRecipeController::class, 'approve']);
    Route::patch('/recipes/{recipe}/reject', [ModeratorRecipeController::class, 'reject']);

    Route::get('/reports', [ModeratorReportController::class, 'index']);
    Route::patch('/comments/{comment}/remove', [ModeratorCommentController::class, 'remove']);
    Route::patch('/comments/{comment}/keep', [ModeratorCommentController::class, 'keep']);
});

Route::middleware(['auth:sanctum', 'role:explorador'])->get('/metrics/explorador', [MetricsController::class, 'explorador']);
Route::middleware(['auth:sanctum', 'role:moderador'])->get('/metrics/moderador', [MetricsController::class, 'moderador']);
