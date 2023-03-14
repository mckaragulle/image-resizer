<?php
namespace Karagulle\ImageResizer\Http\Controllers;

use Karagulle\ImageResizer\ImageResizer;

class PostController extends Controller
{
  public function getPhoto(Request $request){
    $photo = "{$request->name}.{$request->ext}";
    $img = ImageResizer::open($photo, $request->width, $request->height);
    \Log::info($img);
    return $img->response();
  }

}