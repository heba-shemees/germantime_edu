<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// هذا السطر مهم — يحول كل الروابط لـ Vue Router
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
