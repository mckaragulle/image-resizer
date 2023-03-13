<?php

namespace Karagulle\ImageResizer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Image;

class ImageResizer
{
  /**
   * The cache path name of photo files
   * Fotoğraf dosyalarının önbellek yolunun adını döndürür.
   * Ex: public/cache__100_100/
   * @var $writePath
   */
  protected $cachePath = 'public/cache_';

  /**
   * The original path name of photo files
   * Fotoğraf dosyalarının orijinal yolunun adını döndürür.
   * Ex: public/cache__100_100/
   * @var $writePath
   */
  protected $originalPath = 'public/original';

  /**
   * Return default photo name when photo file is not found
   * Fotoğraf dosyası bulunamadığında varsayılan fotoğraf adını döndürür.
   * Fotoğraf yoksa gönderilecek default.png dosyası
   * @var $defaultImg
   */
  protected $defaultImg = 'default.png';

  /**
   * Return the full photo path and photo name
   * Tam fotoğraf yolunu ve fotoğraf adını döndürür.
   * Ex: public/cache__100_100/photo.jpeg
   * @var $writePath
   */
  protected $writePath;

  /**
   * Return the photo name
   * Fotoğrafın adını döndürür.
   * Ex: photo.jpeg
   * @var $filename
   */
  protected $filename;

  /**
   * Return new filetype
   * @var string
   */
  protected $filetype = "webp";

  /**
   * Return resized photo file
   *
   * @param string $filename File Name
   * @param int    $width Photo width
   * @param int    $height Photo height
   *
   * @return bool|string
   */
  public function open(string $filename, int $width = 300, int $height = 300, $filetype = "webp"): bool|string
  {
    $this->originalPath = "public/original/{$filename}";
    $f = explode(".", $filename);
    $this->filename = $f[0];
    $this->width = $width;
    $this->height = $height;
    $this->filetype = $filetype;

    if ($found = $this->checkCache()) {
      return $found;
    }
    try {
      return ($found == false ? $this->resizer($this->originalPath) : $found);
    }
    catch (\Exception $e){
      \Log::error(":::HATA:::{$e->getLine()} : {$e->getMessage()}");
      return $this->getDefault($width, $height);
    }
  }

  /**
   * @param int $width
   * @param int $height
   *
   * @return string
   */
  public function getDefault(int $width, int $height): string
  {
    $this->width = $width;
    $this->height = $height;
    $path = $this->cachePath . $this->width . '_' . $this->height;

    if (!Storage::exists($path)){
      Storage::makeDirectory($path);
      $this->writePath = $path . '/' . $this->defaultImg;
      return $this->resizer();
    }
    else {
      $this->writePath = $this->cachePath . $this->width . '_' . $this->height . '/' . $this->defaultImg;
      $this->resizer('public/original/mountain.jpeg');
      return asset(Storage::url($this->writePath));
    }
  }

  /**
   * Return the original photo file with file path
   * Orijinal fotoğrafı adı ve yolu ile birlikte döndürür.
   *
   * @param $filename
   *
   * @return string
   */
  public function getOriginal(string $filename): string
  {
    return asset(Storage::url("public/original/{$filename}"));
  }

  /**
   * 1- It checks the folder where the file will be saved and if it can't find it, it creates a new one.
   *    Ex: cache_100_100. and returns false.
   * 1- Cache klasörünü kontrol eder, yoksa oluşturur. Ör: cache_100_100. ve false değeri döndürür.
   * 2- Checks if image exists in cache folder.
   *   It is true; Returns its url in the storage directory.
   *   It is false; Returns the false value.
   *   Ex: public/cache_100_100/photo.jpeg
   * 2- Resim cache klasöründe var mı onu kontrol eder.
   *   Doğruysa; Storage dizinindeki url'sini döndürür.
   *   Yoksa; false değerini döndürür.
   *   Ör: public/cache_100_100/photo.jpeg
   * @return bool|string
   */
  protected function checkCache(): bool|string
  {
    $path = "{$this->cachePath}{$this->width}_{$this->height}";
    $this->writePath = "{$path}/{$this->filename}.{$this->filetype}";
    if (!Storage::exists($path)) {
      Storage::makeDirectory($path);
      return false;
    }
    return (Storage::exists($this->writePath) ? asset(Storage::url($this->writePath)) : false);
  }

  /**
   * @param null $inputFile
   *
   * @return string
   */
  protected function resizer($inputFile = null)
  {
    $inputFile = Storage::get(is_null($inputFile)?$this->originalPath:$inputFile);

    $img=Image::make($inputFile)
      ->resize($this->width, $this->height, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      })
      ->encode('webp', 100)
      ->save(Storage::path($this->writePath), 100);

    return asset(Storage::url($this->writePath));
  }
}