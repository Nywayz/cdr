<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CdrController;
use App\Http\Controllers\RequetesController;
use App\Http\Controllers\CallsController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\NeoCallsController;

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
    return view('welcome');
});

Route::get('/cdr', [CdrController::class, 'callsIndividual']);

Route::get('/cdr/time', [CdrController::class, 'timeIndividual']);

Route::get('/cdr/search', [CallsController::class, 'mainSearch']);

Route::get('/cdr/stats/{group?}', [CallsController::class, 'statistics']);
Route::get('/cdr/statsN/{group?}', [NeoCallsController::class, 'stats']);

Route::get('/cdr/monthly', [CallsController::class, 'monthly']);

Route::get('/cdr/statsZoom/{name}/{mode?}/{length?}/{filter?}', [CallsController::class, 'statsZoom']);

Route::get('/cdr/details', [CallsController::class, 'details']);

Route::get('/cdr/lastMinutes/{base?}/{mode?}/{mins?}/{filter?}', [RequetesController::class, 'getLastMinutes']);

Route::get('/cdr/call/{id}', [CallsController::class, 'getCallById']);

Route::get('/admin/panel', [AdminController::class, 'panel'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/cdr/addInSession', [TestController::class, 'addInSession']);
Route::get('/cdr/setInSession', [TestController::class, 'setInSession']);

require __DIR__.'/auth.php';
