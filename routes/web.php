<?php

use Illuminate\Support\Facades\Route;
use SmartCms\Kit\Http\Handlers\PageHandler;
use SmartCms\Kit\Http\Handlers\RobotsHandler;
use SmartCms\Kit\Http\Handlers\SitemapHandler;

Route::get('robots.txt', RobotsHandler::class)->name('robots');
Route::get('sitemap.xml', SitemapHandler::class)->name('sitemap');
// ->multilingual();
Route::get('sitemap/{lang?}.xml', SitemapHandler::class)->name('sitemap.lang');
Route::get('/{slug?}/{second_slug?}/{third_slug?}', PageHandler::class)
    ->where('slug', '^(?!admin|api|_debugbar|.well-known).*$')
    ->where('lang', '[a-zA-Z]{2}')
    ->middleware(['web', 'maintenance', 'uuid', 'lang'])
    ->multilingual()
    ->name('cms.page');
