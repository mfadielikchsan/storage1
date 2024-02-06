<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartController;
use App\Http\Controllers\StockFgController;

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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/generateQr', 'HomeController@generateQr')->name('generateQr');
Route::get('/scanQr', 'HomeController@scanQr')->name('scanQr');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/blank', function () {
    return view('blank');
})->name('blank');

Route::middleware('auth')->group(function () {
    Route::resource('basic', BasicController::class);
    
    Route::prefix('part')->name('part.')->group(function () {
        Route::get('index', [PartController::class, 'index'])->name('index');
        Route::post('store', [PartController::class, 'store'])->name('store');
        Route::delete('delete/{id}', [PartController::class, 'delete'])->name('delete');
        Route::post('upload', [PartController::class, 'upload'])->name('upload');
        Route::get('downloadTemplate', [PartController::class, 'downloadTemplate'])->name('downloadTemplate');
    });
    Route::resource('customer', CustomerController::class);
    Route::resource('gate', GateController::class);
    Route::resource('statusout', StatusOutController::class);
    Route::prefix('stock-fg')->name('stock-fg.')->group(function () {
        Route::get('indexin', [StockFgController::class, 'indexin'])->name('indexin');
        Route::get('indexoutnon', [StockFgController::class, 'indexoutnon'])->name('indexoutnon');
        Route::get('indexoutdeliv', [StockFgController::class, 'indexoutdeliv'])->name('indexoutdeliv');
        Route::get('scanin', [StockFgController::class, 'scanin'])->name('scanin');
        Route::get('scanoutnon', [StockFgController::class, 'scanoutnon'])->name('scanoutnon');
        Route::get('scanoutdeliv', [StockFgController::class, 'scanoutdeliv'])->name('scanoutdeliv');
        Route::get('recapt', [StockFgController::class, 'recapt'])->name('recapt');
        Route::get('getpart/{nopart}', [StockFgController::class, 'getpart'])->name('getpart');
        Route::post('getpartOut', [StockFgController::class, 'getpartOut'])->name('getpartOut');
        Route::post('storeScanin', [StockFgController::class, 'storeScanin'])->name('storeScanin');
        Route::post('storeScanoutNon', [StockFgController::class, 'storeScanoutNon'])->name('storeScanoutNon');
        Route::post('storeScanoutDeliv', [StockFgController::class, 'storeScanoutDeliv'])->name('storeScanoutDeliv');
    });

    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');
});
