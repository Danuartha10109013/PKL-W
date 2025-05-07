<?php

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckDirektur;
use App\Http\Middleware\CheckPegawai;
use App\Http\Middleware\CheckPenerima;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('direktur', [
            CheckDirektur::class,
            
        ]);
        $middleware->appendToGroup('pegawai', [
            CheckPegawai::class,
            
        ]);
        $middleware->appendToGroup('penerima', [
            CheckPenerima::class,
            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
