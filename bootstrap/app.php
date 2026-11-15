<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Mengatur arah redirect otomatis Laravel 12
        $middleware->redirectTo(
            guests: '/masuk',          // Jika belum login tapi akses halaman steril, lempar ke sini
            users: '/beranda'          // JIKA SUDAH LOGIN TAPI AKSES HALAMAN GUEST, LEMPAR KE BERANDA
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
