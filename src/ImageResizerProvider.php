<?php

namespace Karagulle\ImageResizer;

use Illuminate\Support\ServiceProvider;
use Karagulle\ImageResizer\ImageResizer;
use Karagulle\ImageResizer\ImageResizerInterface;

class ImageResizerProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   * @return void
   */
  public function boot()
  {
    $this->publishes([
      __DIR__.'/config/config.php' => config_path('image-resizer.php'),
    ], 'config');
    $this->loadRoutesFrom(__DIR__.'/routes/web.php');
  }

  /**
   * Register services.
   * @return void
   */
  public function register()
  {
    $this->mergeConfigFrom(__DIR__.'/config/config.php', 'image-resizer');
    $this->app->bind(ImageResizerInterface::class, ImageResizer::class);
  }
}
