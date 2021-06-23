<?php

header("Content-Type: image/jpeg");
$f_name = "components/com_jshopping/files/img_products/full_full_akg-k-44-perception.jpg";

$im = imagecreatefromjpeg($f_name);
$x = imagesx($im);      // 500
$dx = $x / 3;           // 100
$dy = 161/927*$dx;      // 161 / 927 * 100 = 17


$logo = imagecreatefrompng("logo.png");  // --- 927 x 161 px
imagecopyresampled($im, $logo, 10, 10, 0, 0, $dx, $dy, 927, 161);

imagejpeg($im);