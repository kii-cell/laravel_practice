<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/admin/posts/detail/{id}', [PostController::class, 'show'])->name('admin.posts.show');
    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts/store', [PostController::class, 'store'])->name('admin.posts.store');
    // 編集フォームの表示
    Route::get('/admin/posts/{id}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
    // 更新処理の実行
    Route::put('/admin/posts/{id}/update', [PostController::class, 'update'])->name('admin.posts.update');
    // 削除処理の実行 (DELETEリクエスト)
    Route::delete('/admin/posts/{id}/delete', [PostController::class, 'destroy'])->name('admin.posts.delete');
});

require __DIR__ . '/auth.php';


Route::get('/my-page', [MyPageController::class, 'index'])->name('my.page');


Route::get('/user/{id}/{mode}', [UserController::class, 'show']);

Route::get('/optional-user/{id?}', [UserController::class, 'show']); // ルートパスを optional-user に変更して区別

Route::get('/about', function () {
    // 直接ビューを返す例
    return view('about');
});
