<?php
if ($_COOKIE["username"]) $username=$_COOKIE["username"];
$loggedin=1;
if ($_COOKIE["usertype"]) $userType=$_COOKIE["usertype"];
if ($_COOKIE["userroom"]) $room=$_COOKIE["userroom"];

if ($userType==3) $admin=1; else $admin=0;

//configure a picture to show when this user is clicked
$userPicture=urlencode("defaultpicture.png");
$userLink=urlencode("http://www.videowhisper.com/");
?>