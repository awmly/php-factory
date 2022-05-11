<?php

namespace STAN\Services\ImageResize;

interface ImageResizeInterface {

  public static function Ratio ($source);

  public static function Watermark ($image_path, $watermark_path);

  public static function Fit ($source, $destination, $nw, $nh);

  public static function Crop ($source, $destination, $nw, $nh);

}
