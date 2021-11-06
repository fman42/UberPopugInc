<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\TaskController::class, 'GetTask'])->name('dashboard');

Route::prefix('task')->group(function ($route) {
    $route->get('/create-task', [App\Http\Controllers\TaskController::class, 'CreateTaskView'])->name('task.create');
    $route->post('/create-task', [App\Http\Controllers\TaskController::class, 'CreateTask'])->name('task.create');
    $route->get('/reassigned-task', [App\Http\Controllers\TaskController::class, 'ReassignTasks'])->name('task.reassigned');

    $route->post('/complete/{id}', [App\Http\Controllers\TaskController::class, 'CompleteTask'])->name('task.complete');
});

Route::get('/auth/redirect', [App\Http\Controllers\MainController::class, 'GetRedirectAuthLink'])->name('home');
Route::get('/callback', [App\Http\Controllers\MainController::class, 'Callback'])->name('callback');