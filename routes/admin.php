<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('/admin')->name('admin.')->group(function () {
    Route::get('/login', function () {
        return Inertia::render('auth/Login');
    })->name('login')->middleware('admin');

    Route::get('/forgot-password', function () {
        return Inertia::render('auth/ForgotPassword');
    })->name('forgot-password')->middleware(['web']);

    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('profile', function () {
            return Inertia::render('admin/Profile');
        })->name('profile');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::delete('profile', [AdminController::class, 'deleteProfile'])->name('profile.destroy');
        Route::put('change-password/{id}', [AdminController::class, 'changePassword'])->name('change-password');
        Route::resource('users', UserController::class)->except(['edit']);
        Route::resource('admins', AdminController::class)->except(['edit']);
        Route::post('admins/multi-delete', [AdminController::class, 'multiDelete'])->name('admins.destroy.many');

        Route::resource('medias', MediaController::class)->only(['index', 'store', 'destroy']);
        Route::post('medias/multi-delete', [MediaController::class, 'multiDelete'])->name('medias.destroy.many');

        Route::resource('roles', RoleController::class)->except(['edit']);

        Route::resource('posts', PostController::class)->except(['edit']);
        Route::post('posts/multi-delete', [PostController::class, 'multiDelete'])->name('posts.multi.destroy');

        Route::resource('categories', CategoryController::class)->except(['edit']);
        Route::post('categories/multi-delete', [CategoryController::class, 'multiDelete'])->name('categories.destroy.many');

        Route::resource('tags', TagController::class)->except(['edit']);

        Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
        Route::post('tiny-mce/upload', [MediaController::class, 'tinyMceUpload'])->name('tinymce-upload');

        Route::resource('products', ProductController::class)->except(['edit']);
        Route::resource('settings', SettingController::class)->only(['index', 'update']);
        Route::resource('attributes', AttributeController::class)->except(['edit']);
        Route::post('chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::post('/broadcasting/auth', function (Illuminate\Http\Request $request) {
            return Broadcast::auth($request);
        });
    });
});
