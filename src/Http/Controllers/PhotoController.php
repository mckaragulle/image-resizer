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
    $photo = "{$request->name}{$request->ext}";
    $cachePhoto = "{$request->name}_{$request->width}_{$request->height}{$request->ext}";
    $cache = config('image-resizer.cachePath')."/{$cachePhoto}";
    $original = config('image-resizer.originalPath')."/{$photo}";
    $default = config('image-resizer.originalPath')."/".config('image-resizer.defaultImg');
    if(Storage::exists($cache)){
      $image = $cache;
    }
    else if(Storage::exists($original))
    {
      $img = new ImageResizer();
      if($img->open($photo, $request->width, $request->height, str_replace(".", "", $request->ext)) != false){
        $image = $original;
      } else {
        $image = $default;
      }
    }
    else{
      $image = $default;
    }
    return Image::make(Storage::get($image))->response();
  }
}