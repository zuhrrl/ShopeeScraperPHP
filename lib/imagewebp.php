<?php

 // convert to webp
 function convertImageToWebP($source, $filename) {
     global $errorCode;
    $imageDirectory = "/assets/images/";
    $file = $source;
    $image = imagecreatefromstring(file_get_contents($file));
    array_push($errorCode, "Call to undefined function imagecreatefromstring()");
    ob_start();
    imagejpeg($image,NULL,100);
    $cont = ob_get_contents();
    ob_end_clean();
    imagedestroy($image);
    $content = imagecreatefromstring($cont);
    $output = $imageDirectory.$filename.'.webp';
    // if file not exist
    if(!file_exists($output)) {
        imagewebp($content,$output);
        imagedestroy($content);
    }
   
    
}


?>