<?php

use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\ProjectController;
use App\Http\Controllers\Site\SeoController;
use App\Http\Controllers\Site\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'redirect']);
Route::get('sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('robots.txt', [SeoController::class, 'robots']);

Route::prefix('{locale}')
    ->whereIn('locale', ['ar', 'en'])
    ->middleware('locale')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

        Route::get('about', [PageController::class, 'about'])->name('about');
        Route::get('contact', [PageController::class, 'contact'])->name('contact');
        Route::get('faq', [PageController::class, 'faq'])->name('faq');
        Route::get('p/{page:slug}', [PageController::class, 'show'])->name('pages.show');
    });
