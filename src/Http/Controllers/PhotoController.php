<?php
namespace Karagulle\ImageResizer\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Karagulle\ImageResizer\ImageResizer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Image;

class PhotoController extends BaseController
{
  public function getPhoto(Request $request){
    $photo = "{$request->name}.{$request->filetype}";
    $cachePhoto = "{$request->name}_{$request->width}_{$request->height}.{$request->filetype}";
    $cache = config('image-resizer.cachePath')."/{$cachePhoto}";
    $original = config('image-resizer.originalPath')."/{$photo}";
    $default = config('image-resizer.originalPath')."/".config('image-resizer.defaultImg');
    if(Storage::exists($cache)){
      $image = $cache;
    }
    else if(Storage::exists($original))
    {
      $img = new ImageResizer();
      if($img->open($photo, $request->width, $request->height, $request->filetype) != false){
        $image = $original;
      } else {
        $image = $default;
      }
    }
    else{
      $image = $default;
    }
    return Image::make(Storage::get($image))
      ->resize($request->width, $request->height, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      })
      ->encode(str_replace('.', '', $request->ext))->response();
  }
}