<?php

namespace Karagulle\Repositories\Interfaces;

use Illuminate\Support\Facades\Facade;

class ImageResizerFacade extends Facade
{

  /**
   * {@inheritDoc}
   */
  protected static function getFacadeAccessor()
  {
    return 'App\Repositories\Interfaces\ImageResizerInterface';
  }
}
