<?php

namespace Karagulle\ImageResizer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

class ImageResizerRepository
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
   * Return resized photo file
   *
   * @param string $filename File Name
   * @param int    $width Photo width
   * @param int    $height Photo height
   *
   * @return bool|string
   */
  public function open(string $filename, int $width = 300, int $height = 300): bool|string
  {
    $this->filename = $filename;

    $this->width = $width;
    $this->height = $height;

    if ($found = $this->checkCache()) {
      return $found;
    }

    $this->originalPath = 'public/original/' . $this->filename;/*dosya var mi kontrol et yoksa, resim yuklenemez; resize edilemez*/
    $found = $this->checkCache();
    return ($found == false ? $this->resizer() : $found);
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

    if (!Storage::exists($path)):
      Storage::makeDirectory($path);
      $this->writePath = $path . '/' . $this->defaultImg;
      return $this->resizer();
    else:
      $this->writePath = $this->cachePath . $this->width . '_' . $this->height . '/' . $this->defaultImg;
      $this->resizer($this->defaultType($type));
      return asset(Storage::url($this->writePath));
    endif;
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
    return asset(Storage::url('public/original/' . $filename));
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
    $path = $this->cachePath . $this->width . '_' . $this->height;
    $this->writePath = $path . '/' . $this->filename;
    if (!Storage::exists($path)) {
      Storage::makeDirectory($path);
      return false;
    }

    try {
      return (Storage::exists($this->writePath) ? asset(Storage::url($this->writePath)) : false);
    } catch (\Exception $e) {
      \Log::error("An error occurred while checking the file: {$e->getMessage()}");
      return false;
    }
  }

  /**
   * @param null $inputFile
   * @param bool $logo Fotoğrafa logo basmak istenirse true, yoksa false girilmelidir.
   *
   * @return string
   */
  protected function resizer($inputFile = null, $logo = true)
  {
    if (is_null($inputFile)) {
      try {
        $img = Image::make(Storage::get($this->originalPath));
        if ($logo)
          $img = $img->insert(public_path('images/logo.png'), 'bottom-right', 0, 0);
        $img = $img->orientate();
      } catch (\Exception $e) {
        Log::error("An error occurred while resizing the image: {$e->getMessage()}");
        return $this->getDefault($this->width, $this->height);
      }
    } else {
      $img = Image::make($inputFile)->orientate();
    }

    $img->resize($this->width, $this->height, function ($constraint) {
      $constraint->aspectRatio();
      $constraint->upsize();
    });
    $img->save(Storage::path($this->writePath));

    $cnv = Image::canvas($this->width, $this->height, '#ffffff');
    $cnv->insert(Storage::path($this->writePath), "center");
    $cnv->save(Storage::path($this->writePath));

    return asset(Storage::url($this->writePath));
  }
}
