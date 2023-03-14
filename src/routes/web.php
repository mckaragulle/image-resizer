<?php

use Illuminate\Support\Facades\Route;
use Karagulle\ImageResizer\Http\Controllers\PostController;

Route::get('/photo/{name}_{width}_{height}.{ext}', 'PhotoController@getPhoto')->name('get.photo');