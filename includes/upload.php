<?php
$target = '/var/www/html/htpc/buttons';
$tmp_name = $_FILES["button_upload"]["tmp_name"];
$name = $_FILES["button_upload"]["name"];
$output = "$target/$name";
move_uploaded_file($tmp_name, $output);

$size = getimagesize($output);
$size = explode(" ", $size[3]);
$width = preg_replace("/[^0-9]/","",$size[0]);
$height = preg_replace("/[^0-9]/","",$size[1]);

if ($width < 360 || $height > 200) { 
  $im = new Imagick($output);
  $im->scaleImage(360, 0);
  $im->cropThumbnailImage(360, 200);
  $im->writeImage($output);
}


?>
