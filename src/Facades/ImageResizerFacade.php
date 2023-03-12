<?php

namespace Karagulle\ImageResizer\Facades\ImageResizer;

use Illuminate\Support\Facades\Facade;

class ImageResizerFacade extends Facade
{

  /**
   * {@inheritDoc}
   */
  protected static function getFacadeAccessor()
  {
    return 'image-resizer';
  }
}
