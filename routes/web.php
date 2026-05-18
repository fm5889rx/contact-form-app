<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () { return view('welcome'); });
Route::get('/', function () {
    return redirect()->route('login');
});

// 仮ルート、Issue #4以降で実装
Route::middleware('auth')->group(function () {
    Route::get('/contents', fn() => 'お問い合わせ一覧（準備中）')->name('contents.index');
    Route::get('/categories', fn() => 'カテゴリー一覧（準備中）')->name('categories.index');
});
