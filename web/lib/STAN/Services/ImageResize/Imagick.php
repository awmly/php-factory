<?php

namespace STAN\Services\ImageResize;

class Imagick implements ImageResizeInterface{

  public static $minQuality = 90;

  public static function Ratio($source){

    $size = filesize($source);

    list($w, $h) = getimagesize($source);
    $pixels = $w * $h;

    $ratio = round($size / $pixels, 3);

    return $ratio;

  }

  public static function Watermark($image_path, $watermark_path) {

    // Open image
    $image = new \Imagick();
    $image->readImage($image_path);

    // Open watermark
    $watermark = new \Imagick();
    $watermark->readImage($watermark_path);

    // Sizes
    $image_width      = $image->getImageWidth();
    $image_height     = $image->getImageHeight();
    $watermark_width  = $watermark->getImageWidth();
    $watermark_height = $watermark->getImageHeight();

    // Calculate watermark coords
    $x = ($image_width - $watermark_width) - 20;
    $y = ($image_height - $watermark_height) - 20;

    // Add watermark to image
    $image->compositeImage($watermark, \Imagick::COMPOSITE_OVER, $x, $y);

    // Save image
    $image->writeImage($image_path); 

  }

  public static function Fit($source, $destination, $nw, $nh, $compress = false, $allow_png = false, $ext = false){

    if(!is_file($source)) return;

    if (!$ext) $ext = Ext($destination);

    if ($ext != 'svg') {

      list($w, $h) = getimagesize($source); // Get the original dimentions

      $imagick = new \Imagick($source);

      self::autorotate($imagick);

      if ($ext == 'gif') {

        if ($nw < $w && $nh < $h) {

          $imagick = $imagick->coalesceImages();

          foreach ($imagick as $frame) {

            if (($h / $nh) < ($w / $nw)) {

              $frame->thumbnailImage($nw, 0);
              $ratio = $nw / $w;
              $nh = ceil($h * $ratio);
    
            } else {
    
              $frame->thumbnailImage(0, $nh);
              $ratio = $nh / $h;
              $nw = ceil($w * $ratio);
    
            }
        
            $frame->setImagePage($nw, $nh, 0, 0);

          }

          $imagick = $imagick->deconstructImages(); 

        }

        $imagick->writeImages($destination, true);

      } else {

        

        if ($nw < $w || $nh < $h) {

          if (($h / $nh) < ($w / $nw)) {

            $imagick->thumbnailImage($nw, 0);

          } else {

            $imagick->thumbnailImage(0, $nh);

          }

        }

        if (($ext == 'jpg' || $ext == 'jpeg') && $compress) {

          $quality = $imagick->getImageCompressionQuality();
          $imageResolution = $imagick->getImageResolution();

          if ($quality > self::$minQuality || $imageResolution['x'] != 72) {
            $imagick->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $imagick->setImageCompressionQuality(self::$minQuality);
            $imagick->setImageResolution(72,72);
            $imagick->setSamplingFactors(array('2x2', '1x1', '1x1'));
            $imagick->setInterlaceScheme(\Imagick::INTERLACE_JPEG);
            $imagick->setColorspace(\Imagick::COLORSPACE_SRGB);
          }

        }

        if (($ext == 'png') && $compress) {

          // if (!$allow_png) {

          //   //echo $imagick->getImageAlphaChannel(); exit;

          //   $imagick->setImageBackgroundColor('white');
          //   //$imagick = $imagick->flattenImages();
          //   $imagick = $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
          //   //$imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_DEACTIVATE); //\Imagick::ALPHACHANNEL_REMOVE //\Imagick::ALPHACHANNEL_DEACTIVATE
          //   //$imagick->setImageBackgroundColor('white');
          //   //$imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
          //   $imagick->setImageFormat('jpg');
          //   $imagick->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
          //   $imagick->setImageCompressionQuality(self::$minQuality);
          //   $imagick->setImageResolution(72,72);
          //   $imagick->setSamplingFactors(array('2x2', '1x1', '1x1'));
          //   $imagick->setInterlaceScheme(\Imagick::INTERLACE_JPEG);
          //   $imagick->setColorspace(\Imagick::COLORSPACE_SRGB);
          //   $destination = str_replace(".png", ".jpg", $destination);
          // }

        }

        $imagick->stripImage();
        $imagick->writeImage($destination);

      }

    }

    $imagick->clear();
    $imagick->destroy();

    return substr(strrchr($destination,"/"),1);

  }


  public static function Crop($source, $destination, $nw, $nh, $quality = 100){

    if(!is_file($source)) return;

    list($w, $h) = getimagesize($source);

    logfile($destination);

    if ($nw == $w && $nh == $h) {

      copy($source, $destination);

    } else {

      $imagick = new \Imagick($source);

      self::autorotate($imagick);

      if (Ext($destination) == 'gif')
      {
        
        if (($h / $nh) > ($w / $nw)) {

          $ratio = $nh / $nw;
          $ch = $h * $ratio;
          $cw = $w;
  
        } else {
  
          $ratio = $nw / $nh;
          $cw = $w * $ratio;
          $ch = $h;
  
        }
  
        $ox = ($w - $cw) / 2;
        $oy = ($h - $ch) / 2;      

        if ($nw < $w && $nh < $h) {

          $imagick = $imagick->coalesceImages();

          foreach ($imagick as $frame) {

            $frame->cropImage($cw, $ch, $ox, $oy);
      
            $frame->thumbnailImage($nw, $nh);
        
            $frame->setImagePage($nw, $nh, 0, 0);

          }

          $imagick = $imagick->deconstructImages(); 

        }

        $imagick->writeImages($destination, true);

      }
      else
      {

        if (($h / $nh) > ($w / $nw)) {

          $imagick->thumbnailImage($nw, 0);
  
        } else {
  
          $imagick->thumbnailImage(0, $nh);
  
        }

        $geom = $imagick->getImageGeometry();
        $neww = $geom['width'];
        $newh = $geom['height'];

        $oy = ($newh - $nh) / 2;
        $ox = ($neww - $nw) / 2;
  
        $imagick->cropImage($nw, $nh, $ox, $oy);

        $imagick->writeImage($destination);

      }

      $imagick->clear();
      $imagick->destroy();
      
      logfile('destroyed');

    }

  }

  public static function autorotate(\Imagick $image)
  {
      switch ($image->getImageOrientation()) {
      case \Imagick::ORIENTATION_TOPLEFT:
          break;
      case \Imagick::ORIENTATION_TOPRIGHT:
          $image->flopImage();
          break;
      case \Imagick::ORIENTATION_BOTTOMRIGHT:
          $image->rotateImage("#000", 180);
          break;
      case \Imagick::ORIENTATION_BOTTOMLEFT:
          $image->flopImage();
          $image->rotateImage("#000", 180);
          break;
      case \Imagick::ORIENTATION_LEFTTOP:
          $image->flopImage();
          $image->rotateImage("#000", -90);
          break;
      case \Imagick::ORIENTATION_RIGHTTOP:
          $image->rotateImage("#000", 90);
          break;
      case \Imagick::ORIENTATION_RIGHTBOTTOM:
          $image->flopImage();
          $image->rotateImage("#000", 90);
          break;
      case \Imagick::ORIENTATION_LEFTBOTTOM:
          $image->rotateImage("#000", -90);
          break;
      default: // Invalid orientation
          break;
      }
      $image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
  }

}
