<?php

use Illuminate\Support\Facades\Route;
use Karagulle\ImageResizer\Http\Controllers\PhotoController;

Route::get('/photo/{name}_{width}_{height}.{ext}', [PhotoController::class, 'getPhoto'])->name('get.photo');