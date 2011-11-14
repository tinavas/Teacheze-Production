<?php
chdir("..");
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if ($_COOKIE["username"]) $username=$_COOKIE["username"];
$loggedin=1;
//if ($_COOKIE["usertype"]) $userType="0"; //$_COOKIE["usertype"];
if ($_COOKIE["userroom"]) $room=$_COOKIE["userroom"];
$userType=0;
if ($userType==3) $admin=1; else $admin=0;

//configure a picture to show when this user is clicked
$userPicture=urlencode("defaultpicture.png");
$userLink=urlencode("http://www.videowhisper.com/");

if (!$room||!$username) {
		$loggedin=0;
		$msg=urlencode("<a href=\"index.php\">You need a cookie enabled browser!</a>");
} else {
		$myroom = db_query("SELECT * FROM {vconf_rooms} WHERE room='%s'",$room);
		if ($myroom !== false) 
		{
			$row = db_fetch_array($myroom);
		}
		else
		{
		$loggedin=0;
		$msg=urlencode("Room $room is not available!");			
		}
}

$rtmp_server=variable_get('vconf_rtmp2', "rtmp://server-domain-or-ip/videowhisper");
if ($rtmp_server=="rtmp://server-domain-or-ip/videowhisper")
{
	$loggedin=0;
	$msg=urlencode("RTMP server not configured!<BR><a href=\"../admin/settings/vconf\">Make sure module is enabled and check admin settings for Drupal, Administer > 2 Way Video Chat.</a>");
}
$rtmp_amf=variable_get('vconf_amf2', "AMF3");
$bufferLive=variable_get('vconf_bufferlive2', "0.5");
$bufferFull=variable_get('vconf_bufferfull2', "0.5");
$bufferLivePlayback=variable_get('vconf_bufferliveplayback2', "0.2");
$bufferFullPlayback=variable_get('vconf_bufferfullplayback2', "0.5");
$disableuploaddetection=variable_get('vconf_disableuploaddetection2', "1");
$disablebandwidthdetection=variable_get('vconf_disablebandwidthdetection2', "1");
$limitbybandwidth=variable_get('vconf_limitbybandwidth2', "1");

$filterRegex=$row[filterregex];
$filterReplace=$row[filterreplace];
$layoutCode=$row[layoutcode];
$background_url=$row[background_url];
$tutorial=$row[tutorial];
$fillwindow=$row[fillwindow];

$autoviewcams='0';
if (user_access('use autoviewcams', $user)) $autoviewcams=$row[autoviewcams];
$panelfiles='0';
if (user_access('use panelfiles', $user)) $panelfiles=$row[panelfiles];
$file_upload='0';
if (user_access('use file_upload', $user)) $file_upload=$row[file_upload];
$showcamsettings='0';
if (user_access('use showcamsettings', $user)) $showcamsettings=$row[showcamsettings];
$file_delete='0';
if (user_access('use file_delete', $user)) $file_delete=$row[file_delete];
$configuresource='0';
if (user_access('use configuresource', $user)) $configuresource=$row[configuresource];
$enabledsound='1';
if (user_access('use enabledsound', $user)) $enabledsound=$row[enabledsound];
$enabledvideo='1';
if (user_access('use enabledvideo', $user)) $enabledvideo=$row[enabledvideo];
$advancedcamsettings='0';
if (user_access('use advancedcamsettings', $user)) $advancedcamsettings=$row[advancedcamsettings];
$showtimer='0';
if (user_access('use showtimer', $user)) $showtimer=$row[showtimer];

//sound and video 
$disabledSound=0;
if (!$enabledsound) $disabledSound=1;
$disabledVideo=0;
if (!$enabledvideo) $disabledVideo=1;

if (!$room) $room="Lobby";

if (!$welcome) $welcome="Welcome to $room! <BR><font color=\"#3CA2DE\">&#187;</font> Click top left preview panel for more options including selecting different camera and microphone. <BR><font color=\"#3CA2DE\">&#187;</font> Click any participant from users list for more options including extra video panels. <BR><font color=\"#3CA2DE\">&#187;</font> Try pasting urls, youtube movie urls, picture urls, emails, twitter accounts as @videowhisper in your text chat. <BR><font color=\"#3CA2DE\">&#187;</font> Download daily chat logs from file list.";

?>server=<?=$rtmp_server?>&serverAMF=<?=$rtmp_amf?>&username=<?=urlencode($username)?>&loggedin=<?=$loggedin?>&userType=<?=$userType?>&administrator=<?=$admin?>&room=<?=urlencode($room)?>&welcome=<?=urlencode($welcome)?>&userPicture=<?=$userPicture?>&userLink=<?=$userLink?>&webserver=&msg=<?=urlencode($msg)?>&tutorial=<?=$tutorial?>&room_delete=0&room_create=0&file_upload=<?=$file_upload?>&file_delete=<?=$file_delete?>&panelFiles=<?=$panelfiles?>&showTimer=<?=$showtimer?>&showCredit=1&disconnectOnTimeout=0&camWidth=<?=$row[camwidth]?>&camHeight=<?=$row[camheight]?>&camFPS=<?=$row[camfps]?>&micRate=<?=$row[micrate]?>&camBandwidth=<?=$row[cambandwidth]?>&limitByBandwidth=<?=$limitbybandwidth?>&showCamSettings=<?=$showcamsettings?>&camMaxBandwidth=<?=$row[cammaxbandwidth]?>&disableBandwidthDetection=<?=$disablebandwidthdetection?>&bufferLive=<?=$bufferLive?>&bufferFull=<?=$bufferFull?>&bufferLivePlayback=<?=$bufferLivePlayback?>&bufferFullPlayback=<?=$bufferFullPlayback?>&advancedCamSettings=<?=$advancedcamsettings?>&configureSource=<?=$configuresource?>&disableVideo=<?=$disableVideo?>&disableSound=<?=$disableSound?>&disableUploadDetection=<?=$disableuploaddetection?>&background_url=<?=$background_url?>&autoViewCams=<?=$autoviewcams?>&layoutCode=<?=urlencode($layoutcode)?>&fillWindow=<?=$fillwindow?>&filterRegex=<?=$filterRegex?>&filterReplace=<?=$filterReplace?>&writeText=1&floodProtection=3&regularWatch=1&newWatch=1&privateTextchat=1&ws_ads=<?=urlencode("ads.php")?>&adsTimeout=15000&adsInterval=0&statusInterval=10000&loadstatus=1
