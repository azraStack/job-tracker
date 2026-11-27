<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('applications.index')
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('applications', JobApplicationController::class);
    Route::resource('companies', CompanyController::class)->only(['index', 'create', 'store', 'show']);
});
