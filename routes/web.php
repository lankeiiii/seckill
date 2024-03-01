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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/home', [App\Http\Controllers\SeckillController::class, 'index'])->name('home');


//在 Laravel 的 throttle 中间件中，第三个参数通常是一个字符串.使用字符串 'user' 作为第三个参数时(默认就是他不写也行)，这是 Laravel 的一个特殊约定，它告诉中间件使用当前经过身份验证的用户的 ID 作为限流键。每分钟60次请求
Route::group(['middleware'=>'throttle:60,1,user','prefix'=>'seckill'],function (){
    Route::get('/index', [\App\Http\Controllers\SeckillController::class,'index']);
    Route::post('/10089', [\App\Http\Controllers\SeckillController::class,'seckill'])->name('seckill_index');
});
