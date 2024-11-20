<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobCardController;
use App\Http\Controllers\KelolaUserController;
use App\Http\Controllers\KomisiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TargetController;
use App\Http\Middleware\AutoLogout;
use Illuminate\Support\Facades\Route;

// Routes for authentication
Route::get('/', [LoginController::class, 'index'])->name('auth.login');
Route::post('/login-proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/cc', [JobCardController::class, 'cc'])->name('cc');


//auto Logout
Route::middleware([AutoLogout::class])->group(function () {


    //profile 
    Route::prefix('profile')->group(function () {
        Route::get('/{id}',[ProfileController::class,'index'])->name('profile');
        Route::post('/update',[ProfileController::class,'update'])->name('profile.update');
    });


    // Admin routes group with middleware and prefix
    Route::group(['prefix' => 'direktur', 'middleware' => ['direktur'], 'as' => 'direktur.'], function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard'); //not same
        Route::prefix('k-user')->group(function () {
            Route::get('/',[KelolaUserController::class,'index'])->name('k-user');
            Route::post('/store',[KelolaUserController::class,'store'])->name('k-user.store');
            Route::put('/update/{id}',[KelolaUserController::class,'update'])->name('k-user.update');
            Route::delete('/destroy/{id}',[KelolaUserController::class,'destroy'])->name('k-user.destroy');
        });

        Route::prefix('komisi')->group(function () {
            Route::get('/',[KomisiController::class,'laporan'])->name('komisi');
            Route::get('/print',[KomisiController::class,'print_laporan'])->name('komisi.print');
        });
        Route::prefix('target')->group(function () {
            Route::get('/',[TargetController::class,'laporan'])->name('target');
        });
    });
    Route::group(['prefix' => 'pegawai', 'middleware' => ['pegawai'], 'as' => 'pegawai.'], function () {
        //Dashboard
        Route::get('/dashboard', [DashboardController::class, 'pegawai'])->name('dashboard'); 
        
        Route::prefix('komisi')->group(function () {
            Route::get('/',[KomisiController::class,'index'])->name('komisi');
            Route::get('/add',[KomisiController::class,'add'])->name('komisi.add');
            Route::get('/jobcards/search', [JobCardController::class, 'searchJobCard'])->name('jobcards.search');
            Route::get('/jobcards/details', [JobCardController::class, 'getJobCardDetails'])->name('jobcards.details');
            Route::post('/komisi/store', [KomisiController::class, 'store'])->name('komisi.store');
            Route::delete('/komisi/delete/{id}', [KomisiController::class, 'delete'])->name('komisi.delete');
            Route::put('/komisi/update/{id}', [KomisiController::class, 'update'])->name('komisi.update');
            Route::get('/komisi_c/print/{id}', [KomisiController::class, 'print_c'])->name('komisi_c.print');
            Route::get('/komisi/print/{id}', [KomisiController::class, 'print'])->name('komisi.print');

        });
        Route::prefix('target')->group(function () {
            Route::get('/',[TargetController::class,'index'])->name('target');
        });
       
    });
    
});