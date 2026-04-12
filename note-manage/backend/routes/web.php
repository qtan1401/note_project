<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

// Các route web (render giao diện) có thể thêm ở đây nếu cần
Route::get('/', function () {
    // Trả về trang auth mặc định nếu chưa vào frontend
    return redirect('/frontend/auth.html');
});
