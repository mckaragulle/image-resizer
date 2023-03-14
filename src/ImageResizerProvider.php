<?php

namespace Karagulle\ImageResizer;

use Illuminate\Support\ServiceProvider;
use Karagulle\ImageResizer\ImageResizerInterface;
use Karagulle\ImageResizer\ImageResizer;

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
    $this->publishes([
      __DIR__.'/../config/config.php' => config_path('image-resizer.php'),
    ]);
    $this->app->bind(ImageResizerInterface::class, ImageResizer::class);
  }
}
