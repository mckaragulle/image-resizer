<?php

namespace Karagulle\ImageResizer\Facades;

use Illuminate\Support\Facades\Facade;

class ImageResizerFacade extends Facade
{

  /**
   * {@inheritDoc}
   */
  protected static function getFacadeAccessor()
  {
    return 'Karagulle\ImageResizer\ImageResizerInterface';
  }
}
