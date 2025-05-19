<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

Route::get('/api-docs/swagger.yaml', function () {
    return response()->file(storage_path('api-docs/swagger.yaml'));
});