<?php

function departments_dropdown($selected=0,$others=true)
{
	$sql = "SELECT * from bd_dept WHERE published='1'";
	
	$queryResult =  db_query($sql);
	
	if($others == true)
	$out = '<option value="">Select Department</option>';
	
	while ($row = db_fetch_object($queryResult)) 
	{
		if ($selected == $row->id)
		{
			$out .= '<option selected="selected" value="'.$row->id.'">'.$row->dept_name.'</option>';
		}
		else
		{
			$out .= '<option value="'.$row->id.'">'.$row->dept_name.'</option>';
		}
	}	
	
	if($others == true)
	$out .= '<option value="0">Others</option>';
	
	return $out;
	
}

function transactions_dropdown($others='true',$all='false')
{
	if(isset($_REQUEST['others']))
	{
		$others = $_REQUEST['others'];
	}
	
	if(isset($_REQUEST['all']))
	{
		$all = $_REQUEST['all'];
	}
	
	if(isset($_REQUEST['selected']))
	{
		$selected = $_REQUEST['selected'];
	}
	else
	{
		$selected = 0;
	}
	
	$dept_id = $_REQUEST['did'];
	
	$sql = "SELECT * from bd_transactions WHERE dept_id='$dept_id' and published='1'";
	
	$queryResult =  db_query($sql);
	
	$out = '<select name="c_transaction" id="c_transaction" onchange="checkOthers();">';
	
	if($all == 'true')
	$out .= '<option value="all">All</option>';
	else if($dept_id!=0)
	$out .= '<option value="">Select Transaction</option>';
	
	while ($row = db_fetch_object($queryResult)) 
	{
		if($row->id==$selected)
		{
			$out .= '<option selected="selected" value="'.$row->id.'">'.$row->trans_name.'</option>';
		}
		else
		{
			$out .= '<option value="'.$row->id.'">'.$row->trans_name.'</option>';
		}
	}
	
	if($others == 'true')
	{		
		$out .= '<option value="0">Others</option>';
	}
	
	$out .= '</select>';
	
	echo $out;
	
	
}

function cities_dropdown($selected='',$all=false)
{
	
	$sql = "SELECT * from bd_city order by city_name";
	
	$queryResult =  db_query($sql);
	
	$out = '<select name="c_city" id="c_city">';
	
	if($all=='true')
	{
		$out.= '<option value="all" selected="selected">All</option>';
	}
	else
	{
		$out.= '<option value="" selected="selected">Select City</option>';
	}
	
	while ($row = db_fetch_object($queryResult)) 
	{
		if($row->Id==$selected)
		{
			$out .= '<option value="'.$row->Id.'" selected="selected">'.$row->city_name.'</option>';
		}
		else
		{
			$out .= '<option value="'.$row->Id.'">'.$row->city_name.'</option>';
		}
	}
	
	$out .= '</select>';
	
	return $out;
	
	
}

function show_captcha_old()
{
	include dirname(__FILE__).'/captcha/securimage.php';

	$img = new securimage();
	
	$img->show(); // alternate use:  $img->show('/path/to/background.jpg');
	exit;
}

//new captcha style
function show_captcha()
{
	include dirname(__FILE__).'/mcaptcha/image.php';
	
	exit;
}

/*date format must be (yyyy-mm-dd hh:mm:ss)*/
function getTimeStampFromDate($date)
{
	$y = substr($date,0,4);
	$m= substr($date,5,2);
	$d = substr($date,8,2);
	$h = substr($date,11,2);
	$mi = substr($date,14,2);
	$s = substr($date,17,2);
	$timestamp = mktime($h,$mi,$s,$m,$d,$y);
	return $timestamp;
}

function getDateFormat($date, $format)
{
	$time = getTimeStampFromDate($date);
	
	return date($format,$time);
}

function getOthers($str)
{
	if(empty($str))
	return 'Others';
	else
	return $str;
}

function sess_mc_write($key,$val)
{
	$ip = ip_address();
	$ses_id = session_id();
	$date_time = date('Y-m-d H:i:s');
	
	sess_mc_remove($key); //remove it before writting again
	
    $sql = "
	INSERT INTO bd_mc_sessions 
	( 
	ip, 
	sesid, 
	seskey, 
	sesval, 
	created
	)
	VALUES
	(
	'$ip',
	'$ses_id', 
	'$key', 
	'$val', 
	'$date_time'
	)";

	$queryResult =  db_query($sql);
	
	return true;
}

function sess_mc_read($key)
{
	$ses_id = session_id();
	
	$sql = "SELECT sesval from bd_mc_sessions where sesid='$ses_id' and seskey='$key'";
	
	$queryResult =  db_query($sql);
	
	return db_result($queryResult);
}

function sess_mc_remove($key)
{
	$ses_id = session_id();
	
	$sql = "DELETE from bd_mc_sessions where sesid='$ses_id' and seskey='$key'";
	
	$queryResult =  db_query($sql);
	
	return true;
}
?>