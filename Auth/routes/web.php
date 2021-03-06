<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('user')->group(function ($route) {
    $route->get('/{id}', [App\Http\Controllers\HomeController::class, 'getUser'])->name('user.get');
    $route->post('/delete', [App\Http\Controllers\HomeController::class, 'deleteUser'])->name('user.delete');
    $route->post('/{id}', [App\Http\Controllers\HomeController::class, 'updateRoleUser'])->name('user.update');
});
