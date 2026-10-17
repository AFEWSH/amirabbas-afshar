<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogImageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectImageController;
use App\Http\Controllers\ResumeDownloadController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SiteAssetController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sharing-image', SiteAssetController::class)->name('site.sharing-image');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/project-images/{image}', ProjectImageController::class)->name('project-images.show');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog-images/{post}', BlogImageController::class)->name('blog-images.show');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');
Route::get('/resume/{language}', ResumeDownloadController::class)->name('resume.download');
