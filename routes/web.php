<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\TaskController;

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
    //記事一覧
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

    //タスク一覧
    Route::get('/admin/tasks', [TaskController::class, 'index'])->name('admin.tasks.index');
    //タスク詳細
    Route::get('/admin/tasks/{id}/detail', [TaskController::class, 'show'])->name('admin.tasks.show');
    //タスク作成
    Route::get('/admin/tasks/create', [TaskController::class, 'create'])->name('admin.tasks.create');
    Route::post('admin/tasks/store', [TaskController::class, 'store'])->name('admin.tasks.store');
    //編集
    Route::get('/admin/tasks/{id}/edit', [TaskController::class, 'edit'])->name('admin.tasks.edit');
    Route::put('/admin/tasks/{id}/update', [TaskController::class, 'update'])->name('admin.tasks.update');
    //削除
    Route::delete('/admin/tasks/{id}/delete', [TaskController::class, 'destroy'])->name('admin.tasks.delete');
    Route::get('/error', function () {
        // 意図的にシステムエラーを発生させる
        throw new \Exception('これはテスト用のシステムエラーです');
    });
});

require __DIR__ . '/auth.php';


Route::get('/my-page', [MyPageController::class, 'index'])->name('my.page');


Route::get('/user/{id}/{mode}', [UserController::class, 'show']);

Route::get('/optional-user/{id?}', [UserController::class, 'show']); // ルートパスを optional-user に変更して区別

Route::get('/about', function () {
    // 直接ビューを返す例
    return view('about');
});
