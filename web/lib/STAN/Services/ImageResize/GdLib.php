<?php

namespace STAN\Services\ImageResize;

class GdLib implements ImageResizeInterface{

  public static function Ratio($source){

    $size = filesize($source);

    list($w,$h) = getimagesize($source);
    $pixels = $w * $h;

    $ratio = round($size / $pixels, 3);

    return $ratio;

  }


  public static function Watermark($image_path, $watermark_path) {
    // to do
  }


  public static function Fit($source, $destination, $nw, $nh, $quality = 100){

        list($w,$h) = getimagesize($source); // Get the original dimentions

        if($nh > $h && $nw > $w)
        {
          $nh = $h;
          $nw = $w;
        }

        $ratio = self::Ratio($source);
        if($ratio < 97)
        {
          $quality = 100;
        }

        //if($nw>=$w && $nh>=$h){

          //copy($source, $destination);

        //}else{

          if(($h/$nh)<($w/$nw)){

            $neww=$nw;
            $r=($nw/$w)*100;
            $newh=(($h/100)*$r); // ADD 1px to eliminate any border issues

          }else{

            $newh=$nh;
            $r=($nh/$h)*100;
            $neww=(($w/100)*$r);

          }

          $thumbnail = imagecreatetruecolor($neww,$newh);

          $ext=explode(".", $source);

          if($ext[count($ext)-1]=='png'){

            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail,true);

            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $nw, $nh, $transparent);

            $img_source = imagecreatefrompng($source);

            imagecopyresampled($thumbnail, $img_source, 0, 0, 0, 0, $neww, $newh, $w, $h);
            imagepng( $thumbnail, $destination);


          }else if($ext[count($ext)-1]=='gif'){

            $img_source = imagecreatefromgif($source);

            imagecopyresampled($thumbnail, $img_source, 0, 0, 0, 0, $neww, $newh, $w, $h);
            imagegif( $thumbnail, $destination, $quality);

          }else{

            $img_source = imagecreatefromjpeg($source);

            imagecopyresampled($thumbnail, $img_source, 0, 0, 0, 0, $neww, $newh, $w, $h);
            imagejpeg( $thumbnail, $destination, $quality);

          }

          imagedestroy($img_source);
          imagedestroy($thumbnail);

        //}

        return substr(strrchr($destination,"/"),1);
  }


  public static function Crop($source, $destination, $nw, $nh, $quality = 100){


        list($w,$h) = @getimagesize($source); // Get the original dimentions

        if($nh > $h && $nw > $w)
        {
          $nh = $h;
          $nw = $w;
        }


        $ratio = self::Ratio($source);
        if($ratio < 97)
        {
          $quality = 100;
        }

        //if($nw==$w && $nh==$h){

          //copy($source, $destination);

        //}else{



          if(($h/$nh)>($w/$nw)){

            $neww=$nw;
            $r=($nw/$w)*100;
            $newh=(($h/100)*$r); // ADD 1px to eliminate any border issues
            $ox=0;
            $oy=($nh-$newh)/2;

          }else{
            $newh=$nh;
            $r=($nh/$h)*100;
            $neww=(($w/100)*$r);
            $oy=0;
            $ox=($nw-$neww)/2;

          }

          $thumbnail = imagecreatetruecolor($nw,$nh); // Creates a new image in memory.

          $ext=explode(".", $source);

          if($ext[count($ext)-1]=='png'){

            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail,true);

            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $nw, $nh, $transparent);

            $img_source = imagecreatefrompng($source);

            imagecopyresampled($thumbnail, $img_source, $ox, $oy, 0, 0, $neww, $newh, $w, $h);
            imagepng( $thumbnail, $destination);


          }else if($ext[count($ext)-1]=='gif'){

            $img_source = imagecreatefromgif($source);

            imagecopyresampled($thumbnail, $img_source, $ox, $oy, 0, 0, $neww, $newh, $w, $h);
            imagegif( $thumbnail, $destination, $quality);

          }else{

            $img_source = imagecreatefromjpeg($source);

            imagecopyresampled($thumbnail, $img_source, $ox, $oy, 0, 0, $neww, $newh, $w, $h);
            imagejpeg( $thumbnail, $destination, $quality);

          }

          imagedestroy($img_source);
          imagedestroy($thumbnail);

        //}

        return substr(strrchr($destination,"/"),1);

  }

}
