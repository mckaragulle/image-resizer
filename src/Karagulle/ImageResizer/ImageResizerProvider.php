<?php

namespace Karagulle\ImageResizer;

use Illuminate\Support\ServiceProvider;
use Karagulle\ImageResizer\ImageResizerInterface;
use Karagulle\ImageResizer\ImageResizerRepository;

class ImageResizerProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   * @return void
   */
  public function boot()
  {
    //
  }

  /**
   * Register services.
   * @return void
   */
  public function register()
  {
    $this->app->singleton(ImageResizerInterface::class, ImageResizerRepository::class);
  }
}
