<?php

use Illuminate\Support\Facades\Route;
use Karagulle\ImageResizer\Http\Controllers\PhotoController;

Route::get('photo/{name}_{filetype}_{width}_{height}{ext}', [PhotoController::class, 'getPhoto'])->where(['name'=> '[a-zA-Z0-9.\-_\/]+', 'ext' => '\..+'])->name('get.photo');