<?php
  ini_set('display_startup_errors',1);
  ini_set('display_errors',1);
  error_reporting(-1);

  $image_file = '7.jpg';
  $src = imagecreatefromjpeg($image_file);

  $resize = array ( // height => widths (array)
        320 => array(
            190,145
        ),
        525 => array(
                960,748,580,470,315,280,230
            ),
        );

    list($width, $height) = getimagesize($image_file);
    foreach($resize AS $resizedHeight=>$resizedWidths) {
        foreach($resizedWidths AS $resizedWidth) {
          
          if($width > $height) {
            resizeLandscapeImage($src, $width, $height,$resizedWidth, $resizedHeight);
          }
          else {
            resizePortraitImage($src, $width, $height,$resizedWidth, $resizedHeight);
          }
        }
    }

  

  function resizeLandscapeImage($src, $imgWidth, $imgHeight, $resizedWidth, $resizedHeight) {
    $name = $resizedWidth.'x'.$resizedHeight.'.jpg';
    echo $name."<br>";
    
    //Get ratio based on Height
    $r = $imgHeight / $resizedHeight;
    
    //Set new height to the expected size (320 or 525) 
    $newHeight = $resizedHeight;
    
    //Resize width with the ratio
    $newWidth = $imgWidth / $r;
    
    //If the resized image has width >= the expected width
    if($newWidth >= $resizedWidth) {
      //Resize image with the exact Height, don't care about width
      $dst = imagecreatetruecolor($newWidth, $newHeight);
      imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $imgWidth, $imgHeight);      
      
      //If width > the expected => crop width
      if($newWidth > $resizedWidth) {
        $startX = floor(($newWidth - $resizedWidth) / 2);
        $startY = 0;

        $dst2 = imagecreatetruecolor($resizedWidth, $resizedHeight);
        imagecopyresampled($dst2, $dst, 0, 0, $startX, $startY, $newWidth, $newHeight, $newWidth, $newHeight);
        imagejpeg($dst2, $name, 100);
      } 
      else {
        imagejpeg($dst, $name, 100);
      }
    }
    else {
      $newR = $r - ($resizedWidth / $newWidth);
      echo $newR." = ".$r." - (".$resizedWidth." / ".$newWidth.")<br>";
      
      $newHeight = $imgHeight / $newR;
      $newWidth = $imgWidth / $newR;      
      
      echo $newHeight." = ".$imgHeight." / ".$newR."<br><br>";
      
      //Resize image with the exact width, don't care about height
      $dst = imagecreatetruecolor($newWidth, $newHeight);
      imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $imgWidth, $imgHeight);
      
      $startX = floor(($newWidth - $resizedWidth) / 2);
      $startY = floor(($newHeight - $resizedHeight) / 2);

      $dst2 = imagecreatetruecolor($resizedWidth, $resizedHeight);
      imagecopyresampled($dst2, $dst, 0, 0, $startX, $startY, $newWidth, $newHeight, $newWidth, $newHeight);
      imagejpeg($dst2, $name, 100);
    }
  }

  function resizePortraitImage($src, $imgWidth, $imgHeight, $resizedWidth, $resizedHeight) {
    $name = $resizedWidth.'x'.$resizedHeight.'.jpg';
    echo $name."<br>";
    
    //Get ratio based on Width
    $r = $imgWidth / $resizedWidth;
    
    //Set new width to the expected size
    $newWidth = $resizedWidth;
    
    //Resize width with the ratio
    $newHeight = $imgHeight / $r;
    
    //Resize image with the exact Height, don't care about width
    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $imgWidth, $imgHeight);

    echo $newWidth."x".$newHeight."<br>";
    
    //If the resized image has height > the expected height ==> crop height
    if($newHeight > $resizedHeight) {
      $startX = 0;
      $startY = floor(($newHeight - $resizedHeight) / 2);
      
      $dst2 = imagecreatetruecolor($resizedWidth, $resizedHeight);
      imagecopyresampled($dst2, $dst, 0, 0, $startX, $startY, $newWidth, $newHeight, $newWidth, $newHeight);
      imagejpeg($dst2, $name, 100);
    }
    else {
      imagejpeg($dst, $name, 100);
    }
  }
?>