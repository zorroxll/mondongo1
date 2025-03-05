<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CocinaController;
use App\Http\Controllers\ListoController;

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

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

Route::get('/cocina', [CocinaController::class, 'index'])->name('cocina.index');
Route::get('/mesero', [ListoController::class, 'index'])->name('mesero.index');