<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TestimonyController;
use App\Http\Controllers\Admin\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->group(function () {

    Route::group(['middleware' => 'auth'], function () {
        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

        // category
        Route::resource('category', CategoryController::class, ['as' => 'admin']);

        // testimony
        Route::resource('testimony', TestimonyController::class, ['as' => 'admin']);

        // Post
        // Route::resource('post', PostController::class, ['as' => 'admin']);
        Route::get('/post', [PostController::class, 'index'])->name('admin.post.index');
        Route::post('/post', [PostController::class, 'store'])->name('admin.post.store');
        Route::get('/post/edit', [PostController::class, 'show'])->name('admin.post.show');
        Route::post('/post/update', [PostController::class, 'update'])->name('admin.post.update');
        Route::delete('/post/delete', [PostController::class, 'destroy'])->name('admin.post.delete');
        Route::post('/post/getCategories', [PostController::class, 'getCategories'])->name('admin.post.getCategories');

        //permission
        Route::get('/permission', [PermissionController::class, 'index'])->name('admin.permission.index');

        //role
        Route::resource('/role', RoleController::class, ['as' => 'admin']);

        //user
        Route::resource('/user', UserController::class, ['except' => ['show'], 'as' => 'admin']);
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/user/showProfile/{id}', [UserController::class, 'showProfile'])->name('user.showProfile');
    Route::post('/user/changePhoto', [UserController::class, 'changePhoto'])->name('user.changePhoto');
});
