<?php

namespace Karagulle\Providers;

use Illuminate\Support\ServiceProvider;
use Karagulle\Repositories\ImageResizerRepository;
use Karagulle\Repositories\Interfaces\ImageResizerInterface;

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
