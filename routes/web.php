<?php

use App\Http\Controllers\CalculationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobCardController;
use App\Http\Controllers\KelolaUserController;
use App\Http\Controllers\KomisiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TargetController;
use App\Http\Middleware\AutoLogout;
use App\Models\KomisiM;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Routes for authentication
Route::get('/', [LoginController::class, 'index'])->name('auth.login');
Route::post('/login-proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/cc', [JobCardController::class, 'cc'])->name('cc');
Route::get('/cek-jobcard', function (\Illuminate\Http\Request $request) {
    $no_jobcard = $request->query('no_jobcard');
    $sudah = \App\Models\KomisiM::where('no_jobcard', $no_jobcard)->exists();
    return response()->json(['sudah' => $sudah]);
});

Route::get('/pegawai/komisi/jobcards/load', function () {
    $response = Http::get('http://127.0.0.1:8001/api/data-api');
    $data = $response->json();

    foreach ($data as &$item) {
        $cek = KomisiM::where('no_jobcard', $item['no_jobcard'])->exists();
        $item['status'] = $cek ? 'Sudah dibuat' : 'Belum dibuat';
    }

    return response()->json($data);
});

Route::get('/pegawai/komisi/notifikasi-jobcard', function () {
    $response = Http::get('http://127.0.0.1:8001/api/data-api');
    $data = $response->json();

    $notifikasi = collect($data)
        ->filter(fn($item) => !KomisiM::where('no_jobcard', $item['no_jobcard'])->exists())
        ->map(function ($item) {
            return [
                'no_jobcard' => $item['no_jobcard'],
                'pesan' => "<a href='/admin/komisi/add?no_jobcard={$item['no_jobcard']}'>Ada Jobcard baru.<br>Nomor Jobcard: <strong>{$item['no_jobcard']}</strong><br>Silakan periksa.</a>"

            ];
        })->values();

    return response()->json([
        'count' => $notifikasi->count(),
        'data' => $notifikasi
    ]);
});

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
        Route::prefix('calculation')->group(function () {
            Route::get('/',[CalculationController::class,'index'])->name('calculation');
            Route::post('/update', [CalculationController::class, 'updateInline'])->name('calculation.update');
            Route::post('/active', [CalculationController::class, 'setActive']);
            Route::post('/store', [CalculationController::class, 'store']);
            Route::delete('/delete/{id}', [CalculationController::class, 'destroy'])->name('calculation.destroy');

        });
        Route::prefix('incentive')->group(function () {
            Route::get('/penerima',[TargetController::class,'penrimaIncentiveDirektur'])->name('incentive');
            // Route::post('/dibayar/{id}/{inId}', [KomisiController::class, 'dibayar'])->name('pegawai.incentive.dibayar');

        });
    });

    
    Route::group(['prefix' => 'admin', 'middleware' => ['pegawai'], 'as' => 'pegawai.'], function () {
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
        Route::prefix('laporan')->group(function () {
            Route::get('/penerima',[TargetController::class,'penrimaIncentive'])->name('penerima.incentive');
            Route::post('/dibayar/{id}/{inId}', [KomisiController::class, 'dibayar'])->name('pegawai.incentive.dibayar');

        });
       
    });

    Route::group(['prefix' => 'penerima', 'middleware' => ['penerima'], 'as' => 'penerima.'], function () {
        //Dashboard
        Route::get('/dashboard', [DashboardController::class, 'penerima'])->name('dashboard'); 
        Route::prefix('komisi')->group(function () {
            Route::get('/',[KomisiController::class,'laporan'])->name('komisi');
            Route::get('/print',[KomisiController::class,'print_laporan'])->name('komisi.print');
        });
        Route::prefix('target')->group(function () {
            Route::get('/',[TargetController::class,'laporan'])->name('target');
        });
        Route::prefix('incentive')->group(function () {
            Route::get('/',[TargetController::class,'incentive'])->name('incentive');
            Route::get('/konfirmasi/{id}/{inId}',[TargetController::class,'confirmation'])->name('incentive.confirmation');
            Route::post('/catatan/{id}/{inId}',[TargetController::class,'catatan'])->name('incentive.catatan');
        });
    });
    
});