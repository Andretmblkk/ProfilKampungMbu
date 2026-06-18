<?php

use App\Http\Controllers\LaporanWargaController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/transparansi', [PublicPageController::class, 'transparency'])->name('transparency');
Route::get('/berita', [PublicPageController::class, 'news'])->name('news.index');
Route::get('/berita/{slug}', [PublicPageController::class, 'newsDetail'])->name('news.show');
Route::get('/laporan/pdf', [PublicPageController::class, 'downloadPdf'])->name('reports.pdf');
Route::get('/laporan-warga', [LaporanWargaController::class, 'create'])->name('citizen-reports.create');
Route::post('/laporan-warga', [LaporanWargaController::class, 'store'])->name('citizen-reports.store');
Route::get('/kontak', [PublicPageController::class, 'contact'])->name('contact');
Route::get('/support', [PublicPageController::class, 'support'])->name('support');
Route::get('/forgot-password', [PublicPageController::class, 'forgotPassword'])->name('password.request');
Route::get('/kebijakan-privasi', [PublicPageController::class, 'privacy'])->name('privacy');
Route::get('/peta-situs', [PublicPageController::class, 'sitemap'])->name('sitemap');
Route::redirect('/login', '/admin/login')->name('login');
