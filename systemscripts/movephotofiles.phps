<?php
include_once('inc_gallery_functions.php');

$Info = RelocateUploadedDemoPhotos("C:\\SHAREDDATA\\SERVERPHOTOS\\Demo Photos Other\\",25);
$Info = RelocateUploadedDemoPhotos("C:\\SHAREDDATA\\SERVERPHOTOS\\Demo Photos\\",25);
//echo "Is Running";
file_put_contents("Runfilelog.log", "\n Run:" . date('Ymd H:i:s') . ' : ' . $Info  , FILE_APPEND);

// ping oh dear
@file_get_contents('https://ping.ohdear.app/2784770a-fd0e-4abe-a5ce-a9a06e9ec23e');