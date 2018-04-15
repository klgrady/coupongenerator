<?php

// Taken from http://php.net/manual/en/image.examples-png.php 
// Called as coupon.php?date=30&url=link for a coupon with 30 days before expiration.

//require_once('../../../../wp-config.php');



//$img_base = "http://testing.klgrady.com/wp/wp-content/uploads/2018/03/klcoupon-1.png";
$expire = $_GET['date'];
$expire = ($expire == "") ? 30 : $expire;
$img_base = $_GET['url'];
$img_base = 'http://' . $img_base;

$date = date_create();
date_add($date,date_interval_create_from_date_string($expire . " days"));
$expire = "Expires " . date_format($date,"m-d-Y");

$im     = imagecreatefrompng($img_base);
$black  = imagecolorallocate($im, 0, 0, 0);
$px     = (imagesx($im) - 7.5 * strlen($expire)) / 2;
$py     = imagesy($im) - 50;
imagestring($im, 10, $px, $py, $expire, $black);

header("Content-type: image/png");
imagepng($im);
imagedestroy($im);
?>