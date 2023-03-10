<?php

namespace App\Providers;

use App\Repositories\ImageResizerRepository;
use App\Repositories\Interfaces\ImageResizerInterface;
use Illuminate\Support\ServiceProvider;

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
