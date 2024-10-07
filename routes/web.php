<?php

use App\Http\Controllers\DashboardController;
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


//auto Logout
Route::middleware([AutoLogout::class])->group(function () {


    //profile 
    Route::prefix('profile')->group(function () {
        Route::get('/{id}',[ProfileController::class,'index'])->name('profile');
        Route::post('/update',[ProfileController::class,'update'])->name('profile.update');
    });


    // Admin routes group with middleware and prefix
    Route::group(['prefix' => 'admin', 'middleware' => ['admin'], 'as' => 'admin.'], function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard'); //not same

        // Manage Employees
        Route::prefix('managepegawai.kelolapegawai')->group(function () {
            
        });
    });
    Route::group(['prefix' => 'pegawai', 'middleware' => ['pegawai'], 'as' => 'pegawai.'], function () {
        //Dashboard
        Route::get('/dashboard', [DashboardController::class, 'pegawai'])->name('dashboard'); 
        
        Route::prefix('komisi')->group(function () {
            Route::get('/',[KomisiController::class,'index'])->name('komisi');
            Route::get('/add',[KomisiController::class,'add'])->name('komisi.add');
        });
        Route::prefix('target')->group(function () {
            Route::get('/',[TargetController::class,'index'])->name('target');
        });
    });
    
});