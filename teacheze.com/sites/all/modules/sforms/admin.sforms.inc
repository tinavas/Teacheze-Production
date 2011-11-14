<?php

function view_paid_bribes()
{
	$query_search = "";
	
	if(isset($_POST['go']))
	{
		$c_dept = $_POST['c_dept'];
		$c_transaction = $_POST['c_transaction'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		
		if($c_dept == 'all')//means all (so no condition)
		{
			$query_search = "";
		}
		else if($c_transaction == 'all')//means all transaction under the selected department
		{
			$query_search = " AND bc.c_dept='$c_dept' ";
		}
		else
		{
			$query_search = " AND bc.c_dept='$c_dept' AND bc.c_transaction='$c_transaction' ";
		}
		
		if(!empty($start_date) && !empty($end_date))
		{
			$query_search .= " AND bc.created >= '$start_date' and DATE(bc.created) <= '$end_date' ";
		}
		
		if($_POST['approved']!='all')
		{
			$query_search .= " AND bc.approved='".$_POST['approved']."'";
		}
		
	}
	
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_paid_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.c_city=ct.Id $query_search order by bc.id desc";
	
	$queryResult =  db_query($sql);
	
	if(isset($_POST['c_dept']))
	{
		$dept_options = departments_dropdown($_POST['c_dept'],false);	
	}
	else
	{
		$dept_options = departments_dropdown(0,false);	
	}
		
	
	return theme('view_paid_bribes',$queryResult,$dept_options);
}

function view_detail_paid_bribes()
{
	$id = $_REQUEST['id'];
	
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_paid_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.id='$id' AND bc.c_city=ct.Id";
	
	$queryResult =  db_query($sql);

	$dept_options = departments_dropdown();
	
	return theme('view_detail_paid_bribes',$queryResult,$dept_options);
}

function add_names()
{
	$name = $_POST['add_names'];
	if($name!=NULL){
	$sql = "SELECT * FROM bd_filter_names WHERE name='".$name."'";
	$queryResult =  db_query($sql);
	if(!(mysql_num_rows($queryResult)>0)){
			$sql = "INSERT into bd_filter_names VALUES ('','".$name."') ";
			$queryResult =  db_query($sql);
	}
	}
	return theme('add_names',$name,$queryResult);
}

function delete_bribe()
{
	$id = $_REQUEST['id'];
	$type = $_REQUEST['type'];
	
	if($type=='paid')
	{
		$sql = "DELETE from bd_paid_bribe WHERE id = '$id'";
		
		$queryResult =  db_query($sql);
		
		drupal_set_message('The record has been deleted successfully');
		
		drupal_goto('admin/sforms/view_paid_bribes');
	}
	else if($type=='raghu')
	{
		$sql = "DELETE from bg_ask_raghu WHERE id = '$id'";
		
		$queryResult =  db_query($sql);
		
		drupal_set_message('The record has been deleted successfully');
		
		drupal_goto('admin/sforms/ask_raghu_list');
	}	
	else if($type=='dinthvtopay')
	{
		$sql = "DELETE from bd_dint_have_to_bribe WHERE id = '$id'";
		
		$queryResult =  db_query($sql);
		
		drupal_set_message('The record has been deleted successfully');
		
		drupal_goto('admin/sforms/view_dint_have_to_pay');
	}
	else
	{
		$sql = "DELETE from bd_dint_bribe WHERE id = '$id'";
		
		$queryResult =  db_query($sql);
		
		drupal_set_message('The record has been deleted successfully');
		
		drupal_goto('admin/sforms/view_not_paid_bribes');
	}
}

function publish_unpublish()
{
	$id = $_REQUEST['id'];
	$type = $_REQUEST['type'];
	$approved = $_REQUEST['published'];
	
	if($approved==1)
	$msg_t = 'published';
	else
	$msg_t = 'unpublished';
	
	if($type=='paid')
	{
		$sql = "UPDATE bd_paid_bribe SET approved='$approved' WHERE id = '$id'";
		
		$queryResult =  db_query($sql);
    
    drupal_set_message('Successfully <strong>'.$msg_t.'</strong> the record');
    
    //set the count for state table for maps display
		$sql_mx_st = 'SELECT MAX(val) as maxv FROM bd_state';
		
		$sql_mx_res =  db_query($sql_mx_st);
		
		$row_mx_row = db_fetch_object($sql_mx_res);
		
		$max_cnt = $row_mx_row->maxv;
		echo "value is----".$max_cnt;
		$sql_st_update = "UPDATE bd_state SET size = ROUND(50*val/$max_cnt)";
		
		$sql_st_upd =  db_query($sql_st_update);
		
		show_state_list();
    		
		drupal_goto('admin/sforms/view_paid_bribes');
	}
	else if($type=='raghu')
	{
		$sql = "UPDATE bg_ask_raghu SET approved='$approved' WHERE id = '$id'";
		
		$queryResult =  db_query($sql);
		
		if($approved==1)
		{
			send_user_email_with_ask_raghu_link($id);
		}
		
		drupal_set_message('Successfully <strong>'.$msg_t.'</strong> the record');
		
		drupal_goto('admin/sforms/ask_raghu_list');		
	}
	else if($type=='dinthvtopay')
	{
		$sql = "UPDATE bd_dint_have_to_bribe SET approved='$approved' WHERE id = '$id'";
		
		$queryResult =  db_query($sql);
		
		drupal_set_message('Successfully <strong>'.$msg_t.'</strong> the record');
		
		drupal_goto('admin/sforms/view_dint_have_to_pay');		
	}	
	else
	{
		$sql = "UPDATE bd_dint_bribe SET approved='$approved' WHERE id = '$id'";
				
		$queryResult =  db_query($sql);
		
		drupal_set_message('Successfully <strong>'.$msg_t.'</strong> the record');
		
		drupal_goto('admin/sforms/view_not_paid_bribes');
	}
	
	
}//eof function publish_unpublish

function view_not_paid_bribes()
{
	$query_search = "";
	
	if(isset($_POST['go']))
	{
		$c_dept = $_POST['c_dept'];
		$c_transaction = $_POST['c_transaction'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		
		if($c_dept == 'all')//means all (so no condition)
		{
			$query_search = "";
		}
		else if($c_transaction == 'all')//means all transaction under the selected department
		{
			$query_search = " AND bc.c_dept='$c_dept' ";
		}		
		else
		{
			$query_search = " AND bc.c_dept='$c_dept' AND bc.c_transaction='$c_transaction' ";
		}
		
		if(!empty($start_date) && !empty($end_date))
		{
			$query_search .= " AND bc.created >= '$start_date' and DATE(bc.created) <= '$end_date' ";
		}
		
		if($_POST['approved']!='all')
		{
			$query_search .= " AND bc.approved='".$_POST['approved']."'";
		}
	}
	
	$sql = "SELECT bc.*,ct.city_name AS c_city,  bd.dept_name, bt.trans_name FROM bd_dint_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id AND bc.c_city=ct.Id $query_search order by bc.id desc";
	
	$queryResult =  db_query($sql);
	
	if(isset($_POST['c_dept']))
	{
		$dept_options = departments_dropdown($_POST['c_dept'],false);	
	}
	else
	{
		$dept_options = departments_dropdown(0,false);	
	}
	
	return theme('view_not_paid_bribes',$queryResult,$dept_options);
}

function view_detail_not_paid_bribes()
{
	$id = $_REQUEST['id'];
	
	$sql = "SELECT bc.*,ct.city_name AS c_city,  bd.dept_name, bt.trans_name FROM bd_dint_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.id='$id' AND bc.c_city=ct.Id";
	
	$queryResult =  db_query($sql);
	
	return theme('view_detail_not_paid_bribes',$queryResult);
}

//functions to get data for dint have to pay bribe
function view_dint_have_to_pay()
{
	$query_search = "";
	
	if(isset($_POST['go']))
	{
		$c_dept = $_POST['c_dept'];
		$c_transaction = $_POST['c_transaction'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		
		if($c_dept == 'all')//means all (so no condition)
		{
			$query_search = "";
		}
		else if($c_transaction == 'all')//means all transaction under the selected department
		{
			$query_search = " AND bc.c_dept='$c_dept' ";
		}
		else
		{
			$query_search = " AND bc.c_dept='$c_dept' AND bc.c_transaction='$c_transaction' ";
		}
		
		if(!empty($start_date) && !empty($end_date))
		{
			$query_search .= " AND bc.created >= '$start_date' and DATE(bc.created) <= '$end_date' ";
		}
		
		if($_POST['approved']!='all')
		{
			$query_search .= " AND bc.approved='".$_POST['approved']."'";
		}
		
	}
	
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_dint_have_to_bribe bc, bd_dept bd, bd_transactions bt,bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.c_city=ct.Id $query_search order by bc.id desc";
	
	$queryResult =  db_query($sql);
	
	if(isset($_POST['c_dept']))
	{
		$dept_options = departments_dropdown($_POST['c_dept'],false);	
	}
	else
	{
		$dept_options = departments_dropdown(0,false);	
	}
	
	return theme('view_dint_have_to_pay',$queryResult,$dept_options);
}

function view_detail_dint_have_to_pay()
{
	$id = $_REQUEST['id'];
	
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_dint_have_to_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.id='$id' and bc.c_city=ct.Id";
	
	$queryResult =  db_query($sql);
	
	return theme('view_detail_dint_have_to_pay',$queryResult);
}

function edit_bribe()
{
    $id = $_REQUEST['id'];
    
    $sql = "select id,c_name,c_addi_info from bd_paid_bribe where id=".$id;
    
    $queryResult =  db_query($sql);
    
    return theme('edit_bribe',$queryResult);
}

function update_bribe()
{
    $id = $_REQUEST['id'];
    
    $title = $_REQUEST['c_name'];
    
    $addi_info = $_REQUEST['addi_info']; 

    $sql = "update bd_paid_bribe set c_name='".htmlentities($title,ENT_QUOTES)."',c_addi_info='".htmlentities($addi_info,ENT_QUOTES)."' where id=".$id;
    
    db_query($sql);
    
    drupal_goto('admin/sforms/view_paid_bribes'); 
}

//list all the questions asked
function ask_raghu_list()
{
	$query_search = "";
	
	if(isset($_POST['go']))
	{
		$c_dept = $_POST['c_dept'];
		$c_transaction = $_POST['c_transaction'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		
		if($c_dept == 'all')//means all (so no condition)
		{
			$query_search = "";
		}
		else if($c_transaction == 'all')//means all transaction under the selected department
		{
			$query_search = " AND br.c_dept='$c_dept' ";
		}
		else
		{
			$query_search = " AND br.c_dept='$c_dept' AND br.c_transaction='$c_transaction' ";
		}
		
		if(!empty($start_date) && !empty($end_date))
		{
			$query_search .= " AND br.created >= '$start_date' and br.created<= '$end_date' ";
		}
		
		if($_POST['approved']!='all')
		{
			$query_search .= " AND br.approved='".$_POST['approved']."'";
		}		
	}
	else if(isset($_POST['asubmit']) && count($_POST['sids']) > 0)
	{
		$sids = implode(',',$_POST['sids']);
		
		$action_d = $_POST['action_d'];
		
		if($action_d == 3)//delete
		{
			$sql_a = "DELETE from bg_ask_raghu WHERE id in ($sids) ";
		}
		else if($action_d == 1 || $action_d == 2)//publish - unpublish
		{
			$sql_a = "UPDATE bg_ask_raghu SET approved='$action_d' WHERE id in ($sids) ";
		}
		
		if(!empty($sql_a))
		{
			db_query($sql_a);
		}
		
		drupal_set_message('Successfully updated the records.');
	}
	
	$sql = "SELECT br.*, bd.dept_name, bt.trans_name FROM bg_ask_raghu br, bd_dept bd, bd_transactions bt WHERE br.c_dept=bd.id AND br.c_transaction=bt.id $query_search order by br.id desc";
	
	//$queryResult =  db_query($sql);
	
	$q_c_res =  db_query($sql);
	
	$num_recs = mysql_num_rows($q_c_res);
	
	$queryResult =  pager_query(db_rewrite_sql($sql), 25);
	
	if(isset($_POST['c_dept']))
	{
		$dept_options = departments_dropdown($_POST['c_dept'],false);	
	}
	else
	{
		$dept_options = departments_dropdown(0,false);	
	}
		
	
	return theme('ask_raghu_list',$queryResult,$dept_options,$num_recs);
	
}//eof ask_raghu_list


//show the details of a particular question asked
function ask_raghu_detail()
{
	$id = $_REQUEST['id'];
	
	if(isset($_REQUEST['submit']))
	{
		
		//$reply_ans = htmlentities($_REQUEST['reply_ans'],ENT_QUOTES);
		
		$reply_ans = $_REQUEST['reply_ans'];
		
		$approved = (!empty($_REQUEST['approved']))?1:0;
		
		$update = "UPDATE bg_ask_raghu set reply_ans='$reply_ans', approved='$approved' WHERE id='$id'";
		
		$queryResult =  db_query($update);
		
		if($approved==1)
		{
			send_user_email_with_ask_raghu_link($id);
		}
		
		drupal_set_message('The reply has been stored successfully.');
		
		drupal_goto('admin/sforms/ask_raghu_list');		
	}
	else
	{
		$sql = "SELECT br.*, bd.dept_name, bt.trans_name FROM bg_ask_raghu br, bd_dept bd, bd_transactions bt WHERE br.c_dept=bd.id AND br.c_transaction=bt.id and br.id='$id'";
		
		$queryResult =  db_query($sql);
		
		return theme('ask_raghu_detail',$queryResult);
	}
	
}//eof ask_raghu_detail


/*
this function will accept question id as parameter and will notify user when his question is answered.
*/

function send_user_email_with_ask_raghu_link($question_id)
{
	global $base_url;
	
	$sql = "SELECT * FROM bg_ask_raghu WHERE id='$question_id'";
	
	$queryResult =  db_query($sql);	
	
	$row = db_fetch_object($queryResult);
	
	$email_id = $row->email_id;
	
	$headers = array('Content-type'=> 'text/html','From'=>'I PAID A BRIBE <noreply@ipaidabribe.com>');
	
	//array('Content-type'=> 'text/html')
	
	$answer_url = $base_url.'/sforms/view_comments_raghu_answers?id='.$row->id;
	

	$search_others_url = $base_url.'/sforms/view_raghu_answers?c_dept='.$row->c_dept.'&c_transaction='.$row->c_transaction.'&fsubmit=fsubmit';
	
	$faq_url = $base_url.'/ipab_forum/';
	
	$mail_text = '
					<p>Dear, '.$email_id .'</p>
					
					<p>Raghu has answered your question on IPAID A BRIBE </p>
					
					<p><strong>Your Question :</strong></p><p>'.nl2br($row->c_question).'. </p>
					
					<p><strong>Raghu\'s Answer :</strong></p>'.html_entity_decode($row->reply_ans).'. 
					
					<p>
					<a href="'.$answer_url.'">Click here</a> to see the answer on ipaidabribe.com</p>
					
					<p>Read Raghu\'s answers to similar questions <a href="'.$search_others_url.'">here</a></p>
					
					<p>
					Do your homework. Read the FAQs <a href="'.$faq_url.'">here</a>.
					</p>
				 ';
						 
	$message = array(
			  'to' => $email_id,
			  'subject' => t('Raghu\'s answer to your question'),
			  'body' => $mail_text,
			  'headers' => $headers,
			);
	
	//echo $mail_text;exit;

	drupal_mail_send($message);
}

function get_update_emails()
{
	if($_REQUEST['csvdwd']=='yes')
	{
		$query='SELECT DISTINCT(email) from bd_register_for_updates';
		$result=db_query($query);
		$email_array=array();
		while($row=db_fetch_object($result)){
			$email_array[]= $row->email;
		}
		
		$text_form_file = implode("\r\n",$email_array);		
		
		//$text_form_file = "Hello \n Dharam \n How are you doing \n Hey";
		
		$fname = dirname(__FILE__).'/emails.txt';
		
		$fp=fopen($fname,'w') or die("can't open file");
		fwrite($fp,$text_form_file);
		fclose($fp);	
		
		output_file($fname, 'emails.txt', 'text/plain');

		exit;
		
	}
	else
	{
		$query_search = "";
		
		$sql = "SELECT * FROM bd_register_for_updates order by id desc";
		
		$queryResult =  db_query($sql);		
		
		return theme('get_update_emails',$queryResult,$dept_options);
	}
	
}//eof function get_update_emails

//function to download files
function output_file($file, $name, $mime_type='')
{
 /*
 This function takes a path to a file to output ($file), 
 the filename that the browser will see ($name) and 
 the MIME type of the file ($mime_type, optional).
 
 If you want to do something on download abort/finish,
 register_shutdown_function('function_name');
 */
 if(!is_readable($file)) die('File not found or inaccessible!');
 
 $size = filesize($file);
 $name = rawurldecode($name);
 
 /* Figure out the MIME type (if not specified) */
 $known_mime_types=array(
 	"pdf" => "application/pdf",
 	"txt" => "text/plain",
 	"html" => "text/html",
 	"htm" => "text/html",
	"exe" => "application/octet-stream",
	"zip" => "application/zip",
	"doc" => "application/msword",
	"xls" => "application/vnd.ms-excel",
	"ppt" => "application/vnd.ms-powerpoint",
	"gif" => "image/gif",
	"png" => "image/png",
	"jpeg"=> "image/jpg",
	"jpg" =>  "image/jpg",
	"php" => "text/plain"
 );
 
 if($mime_type==''){
	 $file_extension = strtolower(substr(strrchr($file,"."),1));
	 if(array_key_exists($file_extension, $known_mime_types)){
		$mime_type=$known_mime_types[$file_extension];
	 } else {
		$mime_type="application/force-download";
	 };
 };
 
 @ob_end_clean(); //turn off output buffering to decrease cpu usage
 
 // required for IE, otherwise Content-Disposition may be ignored
 if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');
 
 header('Content-Type: ' . $mime_type);
 header('Content-Disposition: attachment; filename="'.$name.'"');
 header("Content-Transfer-Encoding: binary");
 header('Accept-Ranges: bytes');
 
 /* The three lines below basically make the 
    download non-cacheable */
 header("Cache-control: private");
 header('Pragma: private');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
 
 // multipart-download and download resuming support
 if(isset($_SERVER['HTTP_RANGE']))
 {
	list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
	list($range) = explode(",",$range,2);
	list($range, $range_end) = explode("-", $range);
	$range=intval($range);
	if(!$range_end) {
		$range_end=$size-1;
	} else {
		$range_end=intval($range_end);
	}
 
	$new_length = $range_end-$range+1;
	header("HTTP/1.1 206 Partial Content");
	header("Content-Length: $new_length");
	header("Content-Range: bytes $range-$range_end/$size");
 } else {
	$new_length=$size;
	header("Content-Length: ".$size);
 }
 
 /* output the file itself */
 $chunksize = 1*(1024*1024); //you may want to change this
 $bytes_send = 0;
 if ($file = fopen($file, 'r'))
 {
	if(isset($_SERVER['HTTP_RANGE']))
	fseek($file, $range);
 
	while(!feof($file) && 
		(!connection_aborted()) && 
		($bytes_send<$new_length)
	      )
	{
		$buffer = fread($file, $chunksize);
		print($buffer); //echo($buffer); // is also possible
		flush();
		$bytes_send += strlen($buffer);
	}
 fclose($file);
 } else die('Error - can not open file.');
 
die();
}	
 
//this function will generate the XML file of states.
function show_state_list()
{
    $sql = "SELECT * FROM bd_state";
    $queryResult =  db_query($sql);
    $fp=fopen('./Event.xml','w+') or die("can't open file");
    $xml='<?xml version="1.0" encoding="iso-8859-1"?>
<Markers>
  ';
  while($row=mysql_fetch_assoc($queryResult))
  {
  $xml.='<Marker id="MRK'.$row['Id'].'">
    ';
    $xml.='<state>'.htmlspecialchars($row['State']).'</state>
    ';
    $xml.='<lat>'.$row['Latitude'].'</lat>
    ';
    $xml.='<lon>'.$row['Longitude'].'</lon>
    ';
    $xml.='<size>'.$row['Size'].'</size>
    ';
    $xml.='<val>'.$row['Value'].'</val>
    ';
    $xml.='<description>'.htmlspecialchars($row['State']).'</description>
    ';
    $xml.='
  </Marker>
  ';
  }
  $xml.='
</Markers>

';

fputs($fp,$xml,100000);
fclose($fp);
}
//eof show_state_list()

function delete_names()
{
	$name = $_POST['delete_names'];
	if($name!=NULL){
	$sql = "SELECT * FROM bd_filter_names WHERE name='".$name."'";
	$queryResult =  db_query($sql);
	if((mysql_num_rows($queryResult)>0)){
			$sql = "DELETE FROM bd_filter_names WHERE name='".$name."'";
			$queryResult =  db_query($sql);
	}
	}
	return theme('delete_names',$name,$queryResult);
}

?>