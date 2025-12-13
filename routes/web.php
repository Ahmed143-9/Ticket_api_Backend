<?php

use Illuminate\Support\Facades\Route;
use App\Models\Configuration\ShortLink;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/s/{code}', function ($code) {
    $original_url = ShortLink::where('code', $code)->pluck('original_url')->first();
    return redirect()->to($original_url);
});
