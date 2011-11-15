<?php
include_once(dirname(__FILE__).'/text_parse.php');

//when user has paid a bribe
function show_registered_complaints()
{		
	//show_state_list();
	
	//echo $securimage_code_value.' - dharam<br />';	
	
	//build_data_file();
	
	//print_r($_SESSION);
	//echo session_id();
	$securimage_code_value = sess_mc_read('sec_code_val');
	sess_mc_remove('sec_code_val');
	$dept_options = departments_dropdown();
	
	$city_dropdown = cities_dropdown();	
		
	if(isset($_REQUEST['fsubmit']))
	{
		if(strtolower($securimage_code_value)!=strtolower($_REQUEST['security_code']))
		{
			if(empty($securimage_code_value))
			{
				$msg = '<p>
							The security code has been expired.
						</p>';
			}
			else
			{
				$msg = '<p>
							Please enter the correct security code
						</p>';
			}
					
			
			
			return theme('show_registered_complaints',$dept_options,$city_dropdown,$msg);	
		}
		else
		{
			if(empty($_REQUEST['c_transaction']))
			{
				$trans_value = '0';
				$other_trans = $_REQUEST['others_transaction'];
			}
			else
			{
				$trans_value = $_REQUEST['c_transaction'];
				$other_trans = '0';
			}
			
			if(empty($_REQUEST['c_dept']))
			{
				$dept_value = '0';
				$others_dept = $_REQUEST['others_dept'];
			}
			else
			{
				$dept_value = $_REQUEST['c_dept'];
				$others_dept = '0';
			}			
			
			$addi_info = t_parse(filtertxt($_REQUEST['c_addi_info']));		
			
			if($addi_info!=$_REQUEST['c_addi_info'])
			{
				$filtered_d = '1';
			}
			else
			{
				$filtered_d = '0';
			}
			
			//echo filtertxt($addi_info);exit;
			
			$nid = db_insert('bd_paid_bribe',
						    array(
							'c_name' => filtertxt($_REQUEST['c_name']),
							'c_city' =>  $_REQUEST['c_city'],
							'c_dept' => $dept_value,
							'others_dept' => filtertxt($others_dept),
							'c_transaction' => $trans_value,
							'others_transaction' => filtertxt($other_trans),
							'c_amt_paid' => $_REQUEST['c_amt_paid'],
							'c_date_paid' => $_REQUEST['c_date_paid'],
							'c_bribe_type' => $_REQUEST['c_bribe_type'],
							'c_addi_info' => $addi_info,
							'other_location' => filtertxt($_REQUEST['other_location']),
							'IP' => $_SERVER['REMOTE_ADDR'],
							'filtered'=>$filtered_d
						  ));
			
			db_insert('bd_paid_bribe_bkp',
						    array(
							'c_name' => filtertxt($_REQUEST['c_name']),
							'c_city' =>  $_REQUEST['c_city'],
							'c_dept' => $dept_value,
							'others_dept' => filtertxt($others_dept),
							'c_transaction' => $trans_value,
							'others_transaction' => filtertxt($other_trans),
							'c_amt_paid' => $_REQUEST['c_amt_paid'],
							'c_date_paid' => $_REQUEST['c_date_paid'],
							'c_bribe_type' => $_REQUEST['c_bribe_type'],
							'c_addi_info' => $_REQUEST['c_addi_info'],
						  ));
	        
			
			//update the data file for reports
			build_data_file();
			//eof updating the data file for reports
			
			return '
					<div class="display_success">

					Thank you for registering the case, <a href="'.url('sforms/register/complaints').'">Click here</a> to add a new case OR <a href="'.url('sforms/view_reports_paid').'">Click here</a> to view your report.
					</div>
					
					<p>
					
					</p>
					';			//save the details
		}

	}
	else
	{
		drupal_set_title('Register a complaint');
		
		return theme('show_registered_complaints',$dept_options,$city_dropdown);
	}
}

	function getURL($url){
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, "$url");
		//curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8');
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$store = curl_exec ($ch);
		curl_close($ch);
		
		$newdoc=json_decode($store);

		return $newdoc;
	}

function albums(){
$content=getURL('https://graph.facebook.com/147734028596537/photos');
$content2=getURL('https://graph.facebook.com/147734028596537');
$albumTitle=$content2->{'name'};
$count=0;
foreach ($content->{'data'} as $photo)
{
	$img_src=$photo->{'source'};
	$thumb_src=$photo->{'picture'};
	$image_name=$photo->{'name'};
	$thumbs[$count]= $img_src;
	$images[$count]= $thumb_src;
	$count++;
}

		drupal_set_title('Albums');
		
		return theme('albums',$thumbs,$images,$albumTitle);
}

//show the registration form to fill, when user has not paid any bribe.
function i_dint_pay_a_bribe()
{	
	$dept_options = departments_dropdown();
	
	$city_dropdown = cities_dropdown();
	
	$securimage_code_value = sess_mc_read('sec_code_val');
	sess_mc_remove('sec_code_val');
	
	if(isset($_REQUEST['fsubmit']))
	{
		if(strtolower($securimage_code_value)!=strtolower($_REQUEST['security_code']))
		{
			if(empty($securimage_code_value))
			{
				$msg = '<p>
							The security code has been expired.
						</p>';
			}
			else
			{
				$msg = '<p>
							Please enter the correct security code
						</p>';
			}
						
			return theme('i_dint_pay_a_bribe',$dept_options,$city_dropdown,$msg);	
		}
		else
		{
			if(empty($_REQUEST['c_transaction']))
			{
				$trans_value = '0';
				$other_trans = $_REQUEST['others_transaction'];
			}
			else
			{
				$trans_value = $_REQUEST['c_transaction'];
				$other_trans = '0';
			}
			
			if(empty($_REQUEST['c_dept']))
			{
				$dept_value = '0';
				$others_dept = $_REQUEST['others_dept'];
			}
			else
			{
				$dept_value = $_REQUEST['c_dept'];
				$others_dept = '0';
			}
			
            $resisted_type = $_REQUEST['resisted_type'];
            
          /*  $resisted_type = $_REQUEST['resisted_type'];
            
            if($resisted_type=='nongovt')
            {
                $resisted_by = $_REQUEST['c_bribe_resisted_by'];
            }
            else
            {
                $resisted_by = 'govt';
            }  */
			
            $resisted_by = $_REQUEST['c_bribe_resisted_by'];
            
			$addi_info = t_parse($_REQUEST['c_addi_info']);
			
			$nid = db_insert('bd_dint_bribe',
						    array(
							'c_name' => filtertxt($_REQUEST['c_name']),
							'c_city' =>  $_REQUEST['c_city'],
							'c_dept' => $dept_value,
							'others_dept' => filtertxt($others_dept),
							'c_transaction' => $trans_value,
							'others_transaction' => filtertxt($other_trans),
							'c_bribe_resisted_by' => $resisted_by,
							'c_addi_info' => filtertxt($addi_info),
							'IP' => $_SERVER['REMOTE_ADDR'],
						  ));
			/*
			data backup in another table
			*/
			
			db_insert('bd_dint_bribe_bkp',
						    array(
							'c_name' => filtertxt($_REQUEST['c_name']),
							'c_city' =>  $_REQUEST['c_city'],
							'c_dept' => $dept_value,
							'others_dept' => filtertxt($others_dept),
							'c_transaction' => $trans_value,
							'others_transaction' => filtertxt($other_trans),
							'c_bribe_resisted_by' => $resisted_by,
							'c_addi_info' => filtertxt($_REQUEST['c_addi_info']),
						  ));
	        
			
						
			return '
					<div class="display_success">
					Thank you for registering the case, <a href="'.url('sforms/register/i_didnt_pay_a_bribe').'">Click here</a> to add a new case OR <a href="'.url('sforms/view_reports_dint_pay').'">Click here</a>
					</div>
					<p>
					
					</p>
					';
			//save the details
		}

	}
	else
	{
		drupal_set_title('I did not pay a bribe');
		
		return theme('i_dint_pay_a_bribe',$dept_options,$city_dropdown);
	}
}


function view_reports_dint_pay()
{
	$query_search = "";
	
	if(isset($_REQUEST['fsubmit']))
	{
		$c_dept = $_REQUEST['c_dept'];
		$c_transaction = $_REQUEST['c_transaction'];
		$c_city = $_REQUEST['c_city'];
		
		if($c_dept == 'all')//means all (so no condition)
		{
			$query_search = "";
		}
		else if($c_transaction == 'all')//means all transaction under the selected department
		{
			$query_search = " AND c_dept='$c_dept' ";
		}
		else
		{
			$query_search = " AND c_dept='$c_dept' AND c_transaction='$c_transaction' ";
		}
		
		if($c_city != 'all')//means all (so no condition)
		{
			$query_search .= " AND c_city='$c_city' ";
		}		
	}
	
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_dint_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id $query_search and bc.approved=1 and bc.c_city=ct.Id order by bc.id desc";
	
	$q_c_res =  db_query($sql);
	
	$num_recs = mysql_num_rows($q_c_res);
		
	$queryResult = pager_query(db_rewrite_sql($sql), 25);
	
	if(isset($_REQUEST['c_dept']))
	{
		$dept_options = departments_dropdown($_REQUEST['c_dept'],true);	
	}
	else
	{
		$dept_options = departments_dropdown(0,true);	
	}
	
	if(isset($_REQUEST['c_city']))
	{
		$city_dropdown = cities_dropdown($_REQUEST['c_city'],true);
	}
	else
	{
		$city_dropdown = cities_dropdown('',true);
	}	
	
	return theme('view_reports_dint_pay',$queryResult,$dept_options,$city_dropdown,$num_recs);
}


function view_reports_dint_have_to_pay()
{
	$query_search = "";
	
	if(isset($_REQUEST['fsubmit']))
	{
		$c_dept = $_REQUEST['c_dept'];
		$c_transaction = $_REQUEST['c_transaction'];
		$c_city = $_REQUEST['c_city'];
		
		if($c_dept == 'all')//means all (so no condition)
		{
			$query_search = "";
		}
		else if($c_transaction == 'all')//means all transaction under the selected department
		{
			$query_search = " AND c_dept='$c_dept' ";
		}
		else
		{
			$query_search = " AND c_dept='$c_dept' AND c_transaction='$c_transaction' ";
		}
		
		if($c_city != 'all')//means all (so no condition)
		{
			$query_search .= " AND c_city='$c_city' ";
		}		
	}
	
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_dint_have_to_bribe bc, bd_dept bd, bd_transactions bt,bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id $query_search and bc.approved=1 and bc.c_city=ct.Id order by bc.id desc";
	
	$q_c_res =  db_query($sql);
	
	$num_recs = mysql_num_rows($q_c_res);
	
	$queryResult = pager_query(db_rewrite_sql($sql), 25);
		
	if(isset($_REQUEST['c_dept']))
	{
		$dept_options = departments_dropdown($_REQUEST['c_dept'],true);	
	}
	else
	{
		$dept_options = departments_dropdown(0,true);	
	}
	
	if(isset($_REQUEST['c_city']))
	{
		$city_dropdown = cities_dropdown($_REQUEST['c_city'],true);
	}
	else
	{
		$city_dropdown = cities_dropdown('',true);
	}	
	
	return theme('view_reports_dint_have_to_pay',$queryResult,$dept_options,$city_dropdown,$num_recs);
}

function view_reports_paid()
{
	$query_search = "";
	
	if(isset($_REQUEST['fsubmit']))
	{
		$c_dept = $_REQUEST['c_dept'];
		$c_transaction = $_REQUEST['c_transaction'];
		$c_city = $_REQUEST['c_city'];
		
		if($c_dept == 'all')//means all (so no condition)
		{
			$query_search = "";
		}
		else if($c_transaction == 'all')//means all transaction under the selected department
		{
			$query_search = " AND c_dept='$c_dept' ";
		}
		else
		{
			$query_search = " AND c_dept='$c_dept' AND c_transaction='$c_transaction' ";
		}
		
		if($c_city != 'all')//means all (so no condition)
		{
			$query_search .= " AND c_city='$c_city' ";
		}
	}
	
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_paid_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.approved=1 and bc.c_city=ct.Id $query_search order by bc.id desc";
	
	$q_c_res =  db_query($sql);
	
	$num_recs = mysql_num_rows($q_c_res);
	
	$queryResult = pager_query(db_rewrite_sql($sql), 25);
	
	if(isset($_REQUEST['c_dept']))
	{
		$dept_options = departments_dropdown($_REQUEST['c_dept'],true);	
	}
	else
	{
		$dept_options = departments_dropdown(0,true);	
	}
	
	if(isset($_REQUEST['c_city']))
	{
		$city_dropdown = cities_dropdown($_REQUEST['c_city'],true);
	}
	else
	{
		$city_dropdown = cities_dropdown('',true);
	}	
	
	return theme('view_reports_paid',$queryResult,$dept_options,$city_dropdown,$num_recs);
}

//ask raghu form
function ask_raghu()
{
	$dept_options = departments_dropdown();
	
	$securimage_code_value = sess_mc_read('sec_code_val');
	sess_mc_remove('sec_code_val');
	
	if(isset($_REQUEST['fsubmit']))
	{
		if(strtolower($securimage_code_value)!=strtolower($_REQUEST['security_code']))
		{
			if(empty($securimage_code_value))
			{
				$msg = '<p>
							The security code has been expired.
						</p>';
			}
			else
			{
				$msg = '<p>
							Please enter the correct security code
						</p>';
			}
					
			return theme('ask_raghu',$dept_options,$msg);	
		}
		else
		{
			
			if(empty($_REQUEST['c_transaction']))
			{
				$trans_value = '0';
				$other_trans = $_REQUEST['others_transaction'];
				$act_trn = $other_trans;
			}
			else
			{
				$trans_value = $_REQUEST['c_transaction'];
				$other_trans = '0';
				$act_trn = getColNameById('bd_transactions','trans_name','id',$trans_value);
			}			
			
			if(empty($_REQUEST['c_dept']))
			{
				$dept_value = '0';
				$others_dept = $_REQUEST['others_dept'];
				$act_dpt = $others_dept;
			}
			else
			{
				$dept_value = $_REQUEST['c_dept'];
				$others_dept = '0';
				$act_dpt = getColNameById('bd_dept','dept_name','id',$dept_value);
			}
			
			$nid = db_insert('bg_ask_raghu',
						    array(
							'c_question' => $_REQUEST['c_question'],
							'c_dept' => $dept_value,
							'others_dept' => $others_dept,
							'c_transaction' => $trans_value,
							'others_transaction' => $other_trans,
							'IP' => $_SERVER['REMOTE_ADDR'],
							'email_id' => $_REQUEST['email_id'],
						  ));
	        
			/*$headers = array(
						'Reply-To'                  => 'webmaster@ipaidabribe.com',
						'From'                      => 'noreply@ipaidabribe.com',
						'MIME-Version'              => '1.0',
						'Content-Type'              => 'text/html; charset=UTF-8; format=flowed; delsp=yes',
						'Content-Transfer-Encoding' => '8Bit',
						'X-Mailer'                  => 'Drupal'
						);*/
			$headers = array('From' => 'noreply@ipaidabribe.com');
			
			$mail_text = '
							<p>Hello Raghu,</p>
							<p>An annonymous user has the following question for you</p>
							<h2>Department :: '.$act_dpt.'</h2>
							<h2>Transaction :: '.$act_trn.'</h2>
							<p>
							'.nl2br($_REQUEST['c_question']).'
							</p>
						 ';
			
			$message = array(
			  'to' => 'dharam.new@gmail.com',
			  'subject' => t('Some one has a question for RAGHU'),
			  'body' => $mail_text,
			  'headers' => $headers,
			);

			drupal_mail_send($message);

			return '
					<div class="display_success">
					Thank you for your question. IPAB will mail you once Raghu has answered it
					</div>

					';
			//save the details
		}

	}
	else
	{
		drupal_set_title('Ask your question to Raghu');
		
		return theme('ask_raghu',$dept_options);
	}
}//eof ask_raghu function

function getColNameById($table,$col_retrive_name,$col_match_name, $col_match_val)
{
   $sql = "SELECT $col_retrive_name from $table WHERE $col_match_name = '$col_match_val'";
   
   $queryResult =  db_query($sql);
   
   return db_result($queryResult);
}

//show Raghu's Answers
function view_raghu_answers()
{
	$query_search = "";
	
	if(isset($_REQUEST['fsubmit']))
	{
		$c_dept = $_REQUEST['c_dept'];
		$c_transaction = $_REQUEST['c_transaction'];
		
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
	}
	  
	  $sql_raghu_ans = "SELECT br.*, bd.dept_name, bt.trans_name FROM bg_ask_raghu br, bd_dept bd, bd_transactions bt WHERE br.c_dept=bd.id AND br.c_transaction=bt.id AND br.approved=1 $query_search order by br.id desc";
	  
	  $q_c_res =  db_query($sql_raghu_ans);
	
	  $num_recs = mysql_num_rows($q_c_res);
	
	  $queryResult = pager_query(db_rewrite_sql($sql_raghu_ans), 20);
  
  
  
	/*
	$sql = "SELECT br.*, bd.dept_name, bt.trans_name FROM bg_ask_raghu br, bd_dept bd, bd_transactions bt WHERE br.c_dept=bd.id AND br.c_transaction=bt.id AND br.approved=1 $query_search order by br.id desc";
	
	$queryResult =  db_query($sql);
	*/
	
	if(isset($_REQUEST['c_dept']))
	{
		$dept_options = departments_dropdown($_REQUEST['c_dept'],false);	
	}
	else
	{
		$dept_options = departments_dropdown(0,false);	
	}
		
	
	return theme('view_raghu_answers',$queryResult,$dept_options,$num_recs);	
}

function hot_topics()
{
	$node_type = $_REQUEST['type'];

	$output = '<h1 class="heading">View Our Hot Topics</h1>';//custom title
	
  $result = pager_query(db_rewrite_sql("SELECT n.nid, n.created FROM {node} n WHERE n.type = 'hot_topic' AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10));
  $has_posts = FALSE;

  while ($node = db_fetch_object($result)) {
    $output .= node_view(node_load($node->nid), 1);
    $has_posts = TRUE;
  }
  
  if ($has_posts) {
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
  }
  else {
    $output.= '<div class="display_info">
        No data found.
         </div>';
  }
	
    return $output;

}

function we_have_got_news()
{
	$node_type = $_REQUEST['type'];

    $output = '<div class="innerhead headblog">
  			  <span><img src="'.base_path() . path_to_theme().'/images/news.png" /></span>
		   </div>
			  
		   <div id="blog">
		   <div class="blog_container">
		  ';		
  $result = pager_query(db_rewrite_sql("SELECT n.nid, n.created FROM {node} n WHERE n.type = 'got_news' AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10));
  $has_posts = FALSE;

  while ($node = db_fetch_object($result)) {
    $output .= node_view(node_load($node->nid), 1);
    $has_posts = TRUE;
  }
  
  if ($has_posts) {
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
	$output.= '</div></div>';
  }
  else {
    $output.= '<div class="display_info">
        No data found.
         </div>';
  }
	
    return $output;

}

function ipab_impact()
{
	$node_type = $_REQUEST['type'];

    $output = '<div class="innerhead headblog">
  			  <span><img src="'.base_path() . path_to_theme().'/images/impact_header.png" /></span>
		   </div>
			  
		   <div id="blog">
		   <div class="blog_container">
		  ';	
  $result = pager_query(db_rewrite_sql("SELECT n.nid, n.created FROM {node} n WHERE n.type = 'ipab_impact' AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10));
  $has_posts = FALSE;

  while ($node = db_fetch_object($result)) {
    $output .= node_view(node_load($node->nid), 1);
    $has_posts = TRUE;
  }
  
  if ($has_posts) {
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
	$output.= '</div></div>';
  }
  else {
    $output.= '<div class="display_info">
        No data found.
         </div>';
  }
	
    return $output;
}

function press()
{
	$node_type = $_REQUEST['type'];

    $output = '<div class="innerhead headblog">
  			  <span><img src="'.base_path() . path_to_theme().'/images/press.png" /></span>
		   </div>
			  
		   <div id="blog">
		   <div class="blog_container">
		  ';		
  $result = pager_query(db_rewrite_sql("SELECT n.nid, n.created FROM {node} n WHERE n.type = 'press' AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10));
  $has_posts = FALSE;

  while ($node = db_fetch_object($result)) {
    $output .= node_view(node_load($node->nid), 1);
    $has_posts = TRUE;
  }
  
  if ($has_posts) {
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
	$output.= '</div></div>';
  }
  else {
    $output.= '<div class="display_info">
        No data found.
         </div>';
  }
	
    return $output;

}

function on_your_case()
{
	$node_type = $_REQUEST['type'];

    $output = '<div class="innerhead headblog">
  			  <span><img src="'.base_path() . path_to_theme().'/images/yourcase.png" /></span>
		   </div>
			  
		   <div id="blog">
		   <div class="blog_container">
		  ';	
  $result = pager_query(db_rewrite_sql("SELECT n.nid, n.created FROM {node} n WHERE n.type = 'on_your_case' AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10));
  $has_posts = FALSE;

  while ($node = db_fetch_object($result)) {
    $output .= node_view(node_load($node->nid), 1);
    $has_posts = TRUE;
  }
  
  if ($has_posts) {
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
	$output.= '</div></div>';
  }
  else {
    $output.= '<div class="display_info">
        No data found.
         </div>';
  }
	
    return $output;

}

function govt_promises()
{
	$node_type = $_REQUEST['type'];

    $output = '<div class="innerhead headblog">
  			  <span><img src="'.base_path() . path_to_theme().'/images/govt_promises.png" /></span>
		   </div>
			  
		   <div id="blog">
		   <div class="blog_container">		   
		   <div class="news">
		   <h2>What is a Citizen\'s Charter?</strong></h2>
		   <div class="news_content">
		   <p style="text-align:justify;">
Citizen\'s Charter is a document which represents a systematic effort to focus on the commitment of the Organisation towards its Citizens in respects of Standard of Services, Information, Choice and Consultation, Non-discrimination and Accessibility, Grievance Redress, Courtesy and Value for Money.  This also includes expectations of the Organisation from the Citizen for fulfilling the commitment of the Organisation. (Source: <a href="http://goicharters.nic.in/faq.htm" target="_blank">http://goicharters.nic.in/faq.htm</a>)
		   </p>
		   </div>
		   </div>
		  ';	
  $result = pager_query(db_rewrite_sql("SELECT n.nid, n.created FROM {node} n WHERE n.type = 'govt_promises' AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10));
  $has_posts = FALSE;

  while ($node = db_fetch_object($result)) {
    $output .= node_view(node_load($node->nid), 1);
    $has_posts = TRUE;
  }
  
  if ($has_posts) {
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
	$output.= '</div></div>';
  }
  else {
    $output.= '<div class="display_info">
        No data found.
         </div>';
  }	
    return $output;

}

function on_air()
{
	$node_type = $_REQUEST['type'];
	
    $args = func_get_args();	  	  	  	  
	  
				
	if(isset($_GET['posted']))
		if($_GET['posted']==1)
			$output='<div><h1>Your video is posted and is awaiting moderation</h1></div>';
			else
			$output='<div><h1>There is some error, please try again later</h1></div>';
		
		
			
    $output .= ' <div class="innerhead">
  			  <span><img src="'.base_path() . path_to_theme().'/images/onair.png" /></span>
			  <a href="'.url('sforms/add_video').'"><img src="'.base_path() . path_to_theme().'/images/addvideo.png" title="view reports" /></a>
			  
		   </div>
			  
		   <div id="blog">
		   <div class="blog_container">
		  ';	
	
	if($args[1]=='vm')
	{
		$output .= '<p>
					<strong>Thank you for submitting the video, it will be published on the website after our approval.</strong>
		            </p>';
	}
	else
	{
		$output .= '';
	}
	
  $result = pager_query(db_rewrite_sql("SELECT n.nid, n.created FROM {node} n WHERE n.type = 'on_air' AND n.status = 1 ORDER BY n.sticky DESC, n.created DESC"), variable_get('default_nodes_main', 10));
  $has_posts = FALSE;

  while ($node = db_fetch_object($result)) {
    $output .= node_view(node_load($node->nid), 1);
    $has_posts = TRUE;
  }
  
  if ($has_posts) {
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
	$output.= '</div></div>';
  }
  else {
    $output.= '<div class="display_info">
        No data found.
         </div>';
  }
	
    return $output;

}//eof function on_air

function sign_pledge()
{
	$sql = "SELECT count(id) total_count FROM bd_sign_pledge";
	
	$queryResult =  db_query($sql);
	$queryResult = mysql_fetch_assoc($queryResult);
	$number_of_people_signed	=	$queryResult['total_count'];
	
	$securimage_code_value = sess_mc_read('sec_code_val');
	sess_mc_remove('sec_code_val');

	if(isset($_REQUEST['fsubmit']))
	{			
		
	/*if(strtolower($securimage_code_value)!=strtolower($_REQUEST['security_code']))
		{
			$msg = '<p>
						Please enter the correct security code
					</p>';
					
			return theme('sign_pledge',$number_of_people_signed,$msg);	
		}
		else
		{*/
			$nid = db_insert('bd_sign_pledge',
						    array(
							'name' => $_REQUEST['client_name'],
							'email' => $_REQUEST['email'],
							'IP' => $_SERVER['REMOTE_ADDR'],
						  ));	      

			return '
					<div class="display_success">
					Thanks for taking the pledge.
					</div>';
			//save the details
		

		//}
	}
	else
	{

		drupal_set_title('Sign Pledge');
		
		return theme('sign_pledge',$number_of_people_signed,$msg);
	}
}//eof sign_pledge function

function register_for_updates()
{	
			
			$chk_email = "SELECT count(*) as cnt from bd_register_for_updates WHERE email='".$_REQUEST['email']."'";
			
			$res_email = db_query($chk_email);
			
			$row_email = db_fetch_object($res_email);
			
			if($row_email->cnt >= 1)
			{
				echo 'You have already registered with us.';
			}
			else
			{
				$nid = db_insert('bd_register_for_updates',
								array(
								'email' => $_REQUEST['email'],
								'IP' => $_SERVER['REMOTE_ADDR'],
							  ));
				
				/*$headers = array(
							'Reply-To'                  => 'webmaster@ipaidabribe.com',
							'From'                      => 'noreply@ipaidabribe.com',
							'MIME-Version'              => '1.0',
							'Content-Type'              => 'text/html; charset=UTF-8; format=flowed; delsp=yes',
							'Content-Transfer-Encoding' => '8Bit',
							'X-Mailer'                  => 'Drupal'
							);*/
	
	
				echo 'You have successfully registered for updates.';
				exit;
			}
	
}//eof register_for_updates function

function i_dint_have_to_pay_a_bribe()
{    
    $dept_options = departments_dropdown();
    
    $city_dropdown = cities_dropdown();
    
	$securimage_code_value = sess_mc_read('sec_code_val');
	sess_mc_remove('sec_code_val');
	
    if(isset($_REQUEST['fsubmit']))
    {
        if(strtolower($securimage_code_value)!=strtolower($_REQUEST['security_code']))
        {
			if(empty($securimage_code_value))
			{
				$msg = '<p>
							The security code has been expired.
						</p>';
			}
			else
			{
				$msg = '<p>
							Please enter the correct security code
						</p>';
			}
            
            
                        
            return theme('i_dint_have_to_pay_a_bribe',$dept_options,$city_dropdown,$msg);    
        }
        else
        {
            if(empty($_REQUEST['c_transaction']))
            {
                $trans_value = '0';
                $other_trans = $_REQUEST['others_transaction'];
            }
            else
            {
                $trans_value = $_REQUEST['c_transaction'];
                $other_trans = '0';
            }
            
            if(empty($_REQUEST['c_dept']))
            {
                $dept_value = '0';
                $others_dept = $_REQUEST['others_dept'];
            }
            else
            {
                $dept_value = $_REQUEST['c_dept'];
                $others_dept = '0';
            }
            
            $resisted_by = $_REQUEST['resisted_type'];
            
           /* if($resisted_type=='nongovt')
            {
                $resisted_by = $_REQUEST['c_bribe_resisted_by'];
            }
            else
            {
                $resisted_by = 'govt';
            } */
            
            $bribe_type = $_REQUEST['c_bribe_type'];
            
			$addi_info = t_parse($_REQUEST['c_addi_info']);
			
            $nid = db_insert('bd_dint_have_to_bribe',
                            array(
                            'c_name' => $_REQUEST['c_name'],
                            'c_city' =>  $_REQUEST['c_city'],
							'c_dept' => $dept_value,
							'others_dept' => filtertxt($others_dept),
                            'c_bribe_type' => $bribe_type, 
                            'c_transaction' => $trans_value,
							'others_transaction' => filtertxt($other_trans),
                            'c_bribe_resisted_by' => $resisted_by,
                            'c_addi_info' => filtertxt($addi_info),
							'IP' => $_SERVER['REMOTE_ADDR'],
                          ));
			
			//bkp of data by dharam.. we cna remove it later
			db_insert('bd_dint_have_to_bribe_bkp',
                            array(
                            'c_name' => $_REQUEST['c_name'],
                            'c_city' =>  $_REQUEST['c_city'],
                            'c_dept' => $dept_value,
                            'c_bribe_type' => $bribe_type, 
                            'c_transaction' => $trans_value,
                            'c_bribe_resisted_by' => $resisted_by,
                            'c_addi_info' => $_REQUEST['c_addi_info'],
                          ));
            
            
                        
            return '
                    <div class="display_success">
                    Thank you for registering the case, <a href="'.url('sforms/register/i_didnt_have_to_pay_a_bribe').'">Click here</a> to add a new case OR <a href="'.url('sforms/view_reports_dint_have_to_pay').'">Click here</a>
					
                    </div>
					
					<p>
					
					</p>
					
                    ';
            //save the details
        }

    }
    else
    {
        drupal_set_title('I did not have to pay a bribe');
        
        return theme('i_dint_have_to_pay_a_bribe',$dept_options,$city_dropdown);
    }
	
}//eof function i_dint_have_to_pay_a_bribe

function poll_answer()
{
	
	/*
	query to get stats
	SELECT COUNT(*) AS num, pa.answer, pa.question_id,pq.question FROM bd_poll_answers pa, bd_poll_questions pq WHERE pq.id=pa.question_id GROUP BY answer, question_id
	*/
	
	
	$dept_options = '';

	if(isset($_REQUEST['fsubmit']))
	{
		
	$ip_a = ip_address();
	
	$poll_id_d = 2;
	
	$chk_ans_by_ip = "SELECT count(1) as cnt from bd_poll_answers WHERE question_id in (SELECT id from bd_poll_questions WHERE poll_id=$poll_id_d ) and ip='$ip_a'";
																						
	$c_a_res =  db_query($chk_ans_by_ip);
   
	$cnt_rec = db_result($c_a_res);		
	
	if($cnt_rec<=0)
	{
	
		for($i=1;$i<=$_REQUEST['number_of_questions'];$i++)
		{
			$question_id=$_REQUEST['SNo'.$i];				
			
			$nid = db_insert('bd_poll_answers',
								array(
								'question_id' => $question_id,
								'answer' => $_REQUEST['option'.$question_id],
								'IP' => $ip_a,
							  ));
			
		}			
		
		$sql = "update bd_poll set count=count+1 WHERE id='$poll_id_d'";
		
		$queryResult =  db_query($sql);
		
		$msg = 'Thank you for answering the questions';
	
	}
	else
	{
		$msg = 'Sorry, we have already recorded a vote from this IP Address.';
	}
	
			/*$headers = array(
						'Reply-To'                  => 'webmaster@ipaidabribe.com',
						'From'                      => 'noreply@ipaidabribe.com',
						'MIME-Version'              => '1.0',
						'Content-Type'              => 'text/html; charset=UTF-8; format=flowed; delsp=yes',
						'Content-Transfer-Encoding' => '8Bit',
						'X-Mailer'                  => 'Drupal'
						);*/			
			//get and prepare the google pier chart result
			
			$sql_ch = 'SELECT COUNT(1) AS num, answer FROM bd_poll_answers WHERE question_id=4 GROUP BY answer';
			
			$res_ch = db_query($sql_ch);
			
			$vote_num = array();
			$vote_txt = array();
			
			$total_cnt = 0;
			
			while ($row = db_fetch_object($res_ch)) 
	  		{
				//devided by 100 as google doesnt provide proper chart when the number is > 1000
				$vote_num[] = $row->num/100;
				
				$total_cnt = $total_cnt + $row->num;
				if($row->answer=='A')
				{
					$vote_txt[] = 'Yes : '.$row->num;
				}
				else if($row->answer=='B')
				{
					$vote_txt[] = 'No : '.$row->num;
				}
				else if($row->answer=='C')
				{
					$vote_txt[] = 'May Be : '.$row->num;
				}
			}
			
			$vote_num_qs = implode(',',$vote_num);
			$vote_txt_qs = implode('|',$vote_txt);
			
			$image_gchart = '<div style="text-align:center;width:100%;"><img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$vote_num_qs.'&chs=300x300&chdl='.$vote_txt_qs.'&chf=bg,s,d2d1b3&chco=b3573e|feed83|fff8d1&chtt=Total votes :: '.$total_cnt.'" /></div>';
			
			//&chtt=
			
			return '
					<div class="display_success">
					'.$msg.'
					</div>
					
					<p>'.$image_gchart.'</p>
					';
			//save the details
		

	}
	else
	{
		$ID=2;
		drupal_set_title('Answer This Poll');
		$query	=	"SELECT * FROM bd_poll WHERE id=".$ID;
   		$queryResult =  db_query($query);
   		$result	=	mysql_fetch_assoc($queryResult);
   		
		$query	=	"SELECT * FROM bd_poll_questions WHERE poll_id=".$ID;
   		$poll_questions =  db_query($query);
		
   		return theme('poll_answer',$result,$poll_questions);
	}
}//eof poll_answers function

function corruption_commons()
{
	$sql = 'SELECT COUNT(1) AS bribes, bs.state AS state_name ,bc.state FROM bd_paid_bribe br, bd_city bc, bd_state bs
WHERE br.approved=1 AND br.c_city=bc.Id AND bc.state=bs.Id GROUP BY bc.state';
	
	$queryResult =  db_query($sql);
	
	$output = '<div class="innerhead headblog">
  			  <span><img src="'.base_path() . path_to_theme().'/images/Corruption_commons.png" /></span>
		   </div>
			  
		   <div id="blog">
		   <div class="blog_container">';
		   
	$output .= theme('corruption_commons',$queryResult);
	
	$output .= '</div></div>';
	
	return $output;
	
}//eof corruption_commons function

function get_comments($type,$type_id)
{
	$sql = "SELECT * from bd_vote_comments where type='$type' and type_id='$type_id' and published=1";
	
	$queryResult =  db_query($sql);
	
	return $queryResult;
}

function get_num_comment($type,$type_id)
{
	$sql = "SELECT count(1) as cnt from bd_vote_comments where type='$type' and type_id='$type_id' and published=1";
	
	$queryResult =  db_query($sql);
	
	$row = db_fetch_object($queryResult);
	
	return $row->cnt;
}

function view_comments_paid()
{
	
	$id = $_REQUEST['id'];	
        
	increment_count("paid_bribe",$id);
	
	$row_det =  get_post_details('paid',$id);
	
	$res_com = get_comments('paid',$id);
	
	return theme('view_comments_paid',$row_det,$res_com);
	
}

function view_comments_dint_pay()
{
	
	$id = $_REQUEST['id'];	
	increment_count("dint",$id);
	
	$row_det =  get_post_details('notpaid',$id);
	
	$res_com = get_comments('notpaid',$id);
	
	return theme('view_comments_dint_pay',$row_det,$res_com);

}

function view_comments_dint_have_to_pay()
{
	
	$id = $_REQUEST['id'];			
	increment_count("dint_have",$id);
	
	$row_det =  get_post_details('dinthvtopay',$id);
	
	$res_com = get_comments('dinthvtopay',$id);
	
	return theme('view_comments_dint_have_to_pay',$row_det,$res_com);
	
}

function view_comments_raghu_answers()
{
	$id = $_REQUEST['id'];			
	
	$row_det =  get_post_details('raghuans',$id);
	
	$res_com = get_comments('raghuans',$id);
	
	return theme('view_comments_raghu_answers',$row_det,$res_com);
	
}

function add_comment()
{
	$securimage_code_value = sess_mc_read('sec_code_val');
	sess_mc_remove('sec_code_val');
	
	if(isset($_POST['fsubmit']))
	{
		if(strtolower($securimage_code_value)!=strtolower($_REQUEST['security_code']))
		{
			if(empty($securimage_code_value))
			{
				$msg = '<p>
							The security code has been expired.
						</p>';
			}
			else
			{
				$msg = '<p>
							Please enter the correct security code
						</p>';
			}					
			
			
			return theme('add_comment_form',$msg);	
		}
		else
		{
			
			
			//save comment
			$nid = db_insert('bd_vote_comments',
								array(
								'type' => $_POST['type'],
								'type_id' => $_POST['type_id'],
								'subject' => filtertxt($_POST['subject']),
								'comment' => filtertxt($_POST['comment']),
								'IP' => $_SERVER['REMOTE_ADDR'],
							  ));
			
			$type = $_POST['type'];
			
			if($type=='paid')
			$redurl = 'sforms/view_reports_paid';
			else if($type=='notpaid')
			$redurl = 'sforms/view_reports_dint_pay';
			else if($type=='dinthvtopay')
			$redurl = 'sforms/view_reports_dint_have_to_pay';
			else if($type=='raghuans')
			$redurl = 'sforms/view_raghu_answers';

			
			return '	<div class="display_success">
						Thank you for posting your comment. You will be redirected in 2 seconds.
						</div>
						
						<script type="text/javascript">
						setTimeout("document.location.href=\''.url($redurl).'\';","2000");
						</script>
					';
		}
	}
	else
	{
		return theme('add_comment_form');
	}
}

function get_post_details($type,$id)
{
	if($type=='paid')
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_paid_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.id = '$id' and bc.c_city=ct.Id";
	else if($type=='notpaid')
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_dint_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.id = '$id' and bc.c_city=ct.Id";
	else if($type=='dinthvtopay')
	$sql = "SELECT bc.*, ct.city_name AS c_city, bd.dept_name, bt.trans_name FROM bd_dint_have_to_bribe bc, bd_dept bd, bd_transactions bt, bd_city ct WHERE bc.c_dept=bd.id AND bc.c_transaction=bt.id and bc.id = '$id' and bc.c_city=ct.Id";	
	else if($type=='raghuans')
	$sql = "SELECT br.*, bd.dept_name, bt.trans_name FROM bg_ask_raghu br, bd_dept bd, bd_transactions bt WHERE br.c_dept=bd.id AND br.c_transaction=bt.id AND br.approved=1 AND br.id='$id'";
	
	$res_det =  db_query($sql);
	
	$row_det =  db_fetch_object($res_det);//only 1 rec details will be there
	
	return $row_det;
}

function filtertxt($str)
{
		// Ensure it's a string
		$str = strval($str);
		// We strip all html tags
		$str = strip_tags($str);
		
		// Remove any whitespace
		//$str = preg_replace('/\s\s+/',' ', $str);
		
		return $str;
}

//this function will create the data file to be used.
function build_data_file()
{
	global $base_path;
		
	$sql = "SELECT rc.c_amt_paid AS amt_paid, bc.city_name AS c_city, bt.dept_name FROM bd_paid_bribe rc, bd_dept bt, bd_city bc 
WHERE rc.c_city!= '' AND rc.c_dept=bt.id AND rc.approved=1 AND rc.c_city=bc.Id";

	$res_det =  db_query($sql);
	
	$data_report = '';
	
	while($row = db_fetch_object($res_det))
	{
		$data_report .= $row->c_city."\t".$row->dept_name."\t".$row->amt_paid."\n";
	}
	
	
	$myFile = $_SERVER['DOCUMENT_ROOT'].$base_path."reports/data.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, $data_report);
	fclose($fh);
	
}

function city_update()
{
	//update city columns with ids
	$sql = "SELECT * from bd_city";
	
	$res_det =  db_query($sql);
	
	while($row = db_fetch_object($res_det))
	{
		$cid = $row->Id;
		$cname = $row->city_name;
		
		//echo '<br />'. $sql1 = "UPDATE bd_paid_bribe set c_city = '$cid' where c_city='$cname'";
		
		//$sql1 = "UPDATE bd_paid_bribe set c_city = '$cid' where c_city='$cname'";
		
		//db_query($sql1);
		
		//echo '<br />'. $sql2 = "UPDATE bd_dint_bribe set c_city = '$cid' where c_city='$cname'";
		
		//$sql2 = "UPDATE bd_dint_bribe set c_city = '$cid' where c_city='$cname'";
		
		//db_query($sql2);
		
		//echo '<br />'.$sql3 = "UPDATE bd_dint_have_to_bribe set c_city = '$cid' where c_city='$cname'";
		
		//$sql3 = "UPDATE bd_dint_have_to_bribe set c_city = '$cid' where c_city='$cname'";
		
		//db_query($sql3);		
	}
}

function search(){
	return $str;
}

function add_video()
{
	require_once(drupal_get_path('module', 'node') . '/node.pages.inc');
	   global $user;
	   $type="on_air";
	   	$_POST['body']=$_POST['embedcode'].$_POST['bodytext'];	

  
  //<object width="640" height="385">
  //<param name="movie" value="http://www.youtube.com/v/6wZ7hr2v8T0?fs=1&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
  //<embed src="http://www.youtube.com/v/6wZ7hr2v8T0?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed>
  //</object>
  
	$new_node = array(
	  'uid' => 0,
	  'name' => $user->name,
	  'type' => $type,
		'errormsg'=> NULL /* replace with your type. */
	/* if you know the names of other fields that you would like to populate you can fill this associative
	* array with those values
	*/
	  );
	  if($_POST['op']=='Submit'){
	  	if($_POST['embedcode']!=NULL)
		{
		if (!preg_match('/^<object(.*)<embed(.*)/', $_POST['embedcode'])) {
		    form_set_error("embedcode", t('Your video appears broken. Please check again<br /><br />'));   
		} 
		}
		else {
		    form_set_error("embedcode", t('Video Embed code field cannot be left blank<br /><br />'));   
		} 
		if ($_POST['title']==NULL) {
		    form_set_error("title", t('Title cannot be left blank<br /><br />'));   
		} 
}
$msg.=
drupal_add_js('sites/all/modules/sforms/js/validation.js', 'core', 'header');
 
 	$errors=form_get_errors();
	
	$output .= $errors["embedcode"];
	$output .= $errors["title"];
	
	 $errortype = (empty($output))?"none":"block";
	
	
	$output = '<div class="innerhead headblog">
  			  <span><img src="'.base_path() . path_to_theme().'/images/onair.png" /></span>
		   </div>
			  
		   <div id="blog">
		   <div class="blog_container">
		   
		   <div id="validation_errors" style="display:'.$errortype.';">
'.$output.'
</div>

		   <script type="text/javascript">
function validate_m()
{
	msg = "";
	
	checkEmptyAll("subject","Please enter subject");
	checkEmptyAll("comment","Please enter your comment.");
			
	return showMessage();
}
</script>
		  ';	

	$output .= drupal_get_form($type ."_node_form", $new_node);
	
	$output .= '</div>
				</div>';

	return $output;
}


function on_air_node_form(&$form_state, $node){
	  global $user;
  if (isset($form_state['node'])) {
    $node = $form_state['node'] + (array)$node;
  }
  $node = (object)$node;
  foreach (array('body', 'title', 'format') as $key) {
    if (!isset($node->$key)) {
      $node->$key = NULL;
    }
  }

    $node->build_mode = NODE_BUILD_PREVIEW;
  

  // Set the id of the top-level form tag
  $form['#id'] = 'node-form';

  // Basic node information.
  // These elements are just values so they are not even sent to the client.
  foreach (array('nid', 'vid', 'uid', 'created', 'type', 'language') as $key) {
    $form[$key] = array(
      '#type' => 'value',
      '#value' => isset($node->$key) ? $node->$key : NULL,
    );
  }
  
      $form['status'] = array(
      '#type' => 'value',
      '#value' => 0,
    );
      $form['format'] = array(
      '#type' => 'value',
      '#value' => 2,
    );
  
      $form['comment'] = array(
      '#type' => 'value',
      '#value' => 2,
    );
    
  $form['#node'] = $node;
  $form['#redir'] = 'sforms/on_air/vm';
  

  // These values are used when the user has no administrator access.
  foreach (array('uid', 'created') as $key) {
    $form[$key] = array(
      '#type' => 'value',
      '#value' => $node->$key,
    );
  }

  // Add the buttons.
  $form['buttons'] = array();
  
    $form['title'] = array(
  '#type' => 'textfield',
  '#title' => t('Title'),
  '#required' => TRUE,
  '#description' => t('Put the title of the video here '),
);
  
  $form['embedcode'] = array(
  '#type' => 'textarea',
  '#title' => t('Embed Code'),
   '#required' => TRUE,
  '#description' => t('Add the embed code here'),
);

  $form['bodytext'] = array(
  '#type' => 'textarea',
  '#title' => t('Body'),
  '#description' => t('Put the video description here (optional)'),
);

  $form['body'] = array(
  '#type' => 'hidden',
);
  
  $form['buttons']['submit'] = array(
    '#type' => 'submit',
    '#access' => !variable_get('node_preview', 0) || (!form_get_errors() && isset($form_state['node_preview'])),
    '#value' => t('Submit'),
    '#weight' => 5,
    '#submit' => array('node_form_submit'),
  );


  $form['#theme'] = array($node->type .'_node_form', 'node_form');
  return $form;
}

function increment_count($tableName,$SNO)
{
	if($tableName==null)
		$tableName=$_GET['tableName'];
	if($SNO==null)
		$SNO=$_GET['SNO'];
		
	if($tableName=="paid_bribe")
		$table="bd_paid_bribe";
	if($tableName=="dint")
		$table="bd_dint_bribe";
	if($tableName=="dint_have")
		$table="bd_dint_have_to_bribe";
		
	$sql = "UPDATE $table SET count=count+1 where id=$SNO";
	
	$res_det =  db_query($sql);
	
	//echo $sql;
	return $res_det;

}

function get_g_view_cnt()
{
	$sql = "SELECT pagevisit from google_analytics";
	$queryResult =  db_query($sql); 
	?>
    <span id="hit_num"><?php echo db_result($queryResult);?></span>
    <span>HITS</span>
    <?php	    
	exit;
}
?>
