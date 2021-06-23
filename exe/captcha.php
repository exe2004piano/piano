<?php

$image = imagecreatetruecolor(50, 24);
$fon = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $fon);
$text_color = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 4, 5, 5,  base64_decode($_GET['text']), $text_color);

header('Content-type: image/png');
imagepng($image);

?>