<?php
/**
* fn for displaying dashboard
* return dashboard view page
*/
function ulearn_dashboard () {
	global $user;
	$date = date('Y-m-d');
	$res = db_fetch_object ( db_query("SELECT paid FROM student_booking WHERE sid = " . $user->uid . " AND paid = 1 AND booked_on_date = '" . $date . "'"));
	return theme('ulearn_dashboard', $res);
}

/**
* fn for delete package
* return success mess
*/
function ulearn_remove_time () {
	global $base_url;
	$id 	= $_REQUEST['id'];
	$pid 	= $_REQUEST['pid'];
	$sql 	= db_query("DELETE from {package_times} WHERE id = '$id'");
	drupal_set_message('The Package time has been removed successfully');
	drupal_goto( $base_url . '/edit_package?id=' . $pid );
}


/**
* fn for ensure it's a string and strip all html tags
* return string
*/
function filtertxtb($str) {
	// Ensure it's a string
	$str = strval($str);
	// We strip all html tags
	$str = strip_tags($str);
	// Remove any whitespace
	//$str = preg_replace('/\s\s+/',' ', $str);
	return $str;
}

/**
* fn for displaying list of teachers
* return result set
*/
function ulearn_list_teachers () {
	global $user;
	$sql = "SELECT * FROM {users} AS u LEFT JOIN {users_roles} AS ur ON ur.uid = u.uid WHERE ur.rid = 4";
	$res = pager_query($sql, 10);
	return theme('ulearn_list_teachers', $res);
}

/**
* fn for displaying selected lesson details
* return result set
*/
function ulearn_view_package () {
	$res = db_query("SELECT * FROM {packages} WHERE id = " . $_REQUEST['lid']);
	return theme('ulearn_view_package', $res);
}

/**
* fn for displaying selected package details on confirm page
* return result set
*/
function ulearn_buy_package () {
	$res = db_fetch_object ( db_query("SELECT *, pt.id as ptid, p_id as pid FROM {package_times} AS pt LEFT JOIN {packages} AS p ON p.id = pt.p_id WHERE pt.id = " . $_REQUEST['id']));
	return theme('ulearn_buy_package', $res);
}

function ulearn_my_transactions () {
	global $user;

	$str = '';
	if ($_REQUEST['name']) {
		$str = " AND u.name LIKE '%%".$_REQUEST['name']."%%'";
	}

	if ( $user->user_selectable_roles == 4 ) {

		$sql = "SELECT s.*, u.name AS uname, u.uid, sub.subject AS subject
				FROM student_booking s
				LEFT JOIN ul_users AS u ON u.uid = s.sid
				LEFT JOIN teacher_subjects_master AS sub ON sub.sub_id = s.subject_id
				WHERE tid = " . $user->uid . $str . "
				ORDER BY s.duration_mins";
		$res = pager_query($sql, 10);

	} else {

		$sql = "SELECT s.*, u.name AS uname, u.uid, sub.subject AS subject
				FROM student_booking s
				LEFT JOIN ul_users AS u ON u.uid = s.tid
				LEFT JOIN teacher_subjects_master AS sub ON sub.sub_id = s.subject_id
				WHERE sid = " . $user->uid . $str . "
				ORDER BY s.duration_mins";
		$res = pager_query($sql, 10);

	}

	return theme('ulearn_my_transactions', $res);
}


function ulearn_check_regstep2(){
	global $user;
	if($user->user_selectable_roles == 4){
		$res = db_fetch_object(db_query("SELECT * FROM teacher_available WHERE  tid = " . $user->uid));
		if($res->tid == $user->uid){
			drupal_goto('user/' . $user->uid . '/edit');
		}else{
			drupal_goto('gotoregstep2');
		}
	} else {
		drupal_goto('user/' . $user->uid . '/edit');
	}
}

function ulearn_gotoregstep2(){
	global $user;
	$qry = "SELECT tid FROM teacher_lession_prices WHERE tid=".$user->uid;
	$res = db_query($qry);
	$i = 0;
	while($row = db_fetch_array($res)){
		if($row['tid'] == $user->uid){
			$i++;
		}
	}
	if($i > 0){
		$profileurl = 'user/'.$user->uid.'/edit';
		drupal_goto($profileurl);
	}
	if(isset($_POST['save'])){
		$day = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		$Iqry = $Ival = '';
		$Gqry = $Gval = '';
		for($i = 1; $i <= 7; $i++) {
			//Individual selected times
			if(isset($_POST['Individual_'.$i])) {
				$Iqry .= " {$day[($i-1)]}, ";
				$Ival .= " '".$_POST['Individual_'.$i]."', ";
			}
			//Group selected times
			if(isset($_POST['Group_'.$i])) {
				$Gqry .= " {$day[($i-1)]}, ";
				$Gval .= " '".$_POST['Group_'.$i]."', ";
			}
		}

		if($Iqry != '') {
			$Iqry = "insert into teacher_available (tid, lesson_type, ".substr($Iqry,0,strlen($Iqry)-2).")";
			$Iqry .= " values ({$_POST['tid']},'I', ".substr($Ival,0,strlen($Ival)-2)." )";
		}
		else
		{
			$Iqry = "insert into teacher_available (tid, lesson_type)";
			$Iqry .= " values ({$_POST['tid']},'I' )";
		}
		if($Gqry != '') {
			$Gqry = "insert into teacher_available (tid, lesson_type, ".substr($Gqry,0,strlen($Gqry)-2).")";
			$Gqry .= " values ({$_POST['tid']},'G', ".substr($Gval,0,strlen($Gval)-2)." )";
		}
		else {
			$Gqry = "insert into teacher_available (tid, lesson_type)";
			$Gqry .= " values ({$_POST['tid']},'G')";
		}
		

		$Isql = db_query($Iqry);
		$Gsql = db_query($Gqry);

		//Subjects, individual & group lessions price
		if($_POST['subject1'] != 0)
		$subject1=$_POST['subject1'];
		if($_POST['subject2'] != 0)
		$subject2=$_POST['subject2'];
		if($_POST['subject3'] != 0)
		$subject3=$_POST['subject3'];

		$qry = "insert into teacher_lession_prices (tid, subject1, subject2, subject3, price_of_30min, price_of_45min, group_lesson_45min, free_trail_lession) values ('".$_POST['tid']."', '".$subject1."', '".$subject2."', '".$subject3."', '".$_POST['price_of_30min']."', '".$_POST['price_of_45min']."', '".$_POST['group_lesson_45min']."', '0')";
		db_query($qry);
		

		$pack_qry="INSERT INTO teacher_discount_packages (
		`tid`, 
		`10_less_30min_discount`, 
		`10_less_45min_discount`, 
		`30_less_30min_discount`, 
		`30_less_45min_discount`, 
		`50_less_30min_discount`, 
		`50_less_45min_discount`, 
		`100_less_30min_discount`, 
		`100_less_45min_discount`
		
		) VALUES (
		'{$_POST['tid']}', 
		'".$_POST['10_less_30min_discount']."', 
		'".$_POST['10_less_45min_discount']."', 
		'".$_POST['30_less_30min_discount']."', 
		'".$_POST['30_less_45min_discount']."', 
		'".$_POST['50_less_30min_discount']."', 
		'".$_POST['50_less_45min_discount']."', 
		'".$_POST['100_less_30min_discount']."', 
		'".$_POST['100_less_45min_discount']."'
		)";
	
		db_query($pack_qry);
		drupal_set_message('Teacher Available Schedule added successfully');
		$profileurl = 'user/'.$user->uid.'/edit';
		drupal_goto($profileurl);
	} else {
		return theme('ulearn_gotoregstep2');
	}
}

function ulearn_edit_package(){
	
	global $user;

	if(isset($_POST['save'])){
	
		$day = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		$Iqry = $Ival = '';
		$Gqry = $Gval = '';
		//$row_count = db_affected_rows(db_query("select * from teacher_available where tid=".$user->uid));
		//if($row_count>0)
		//{
			for($i = 1; $i <= 7; $i++) {
			//Individual selected times
				if(isset($_POST['Individual_'.$i])) {
					 $Iqry[$i] = " {$day[($i-1)]} ";
					 $Ival[$i]= " '".$_POST['Individual_'.$i]."' ";				
					 $ival = $Iqry[$i]."=".$Ival[$i].", ";		
					 $iuquery.=$ival;
				}
				//Group selected times
				if(isset($_POST['Group_'.$i])) {
					$Gqry[$i]= " {$day[($i-1)]} ";
					$Gval[$i]= " '".$_POST['Group_'.$i]."' ";
					$gval = $Gqry[$i]."=".$Gval[$i].", ";		
					$guquery.=$gval;
				}
			}
			$iuquery = rtrim($iuquery,', =, ');
			$guquery = rtrim($guquery,', =, ');
			
			
			
			
			if($iuquery != '') {
				$query = "UPDATE teacher_available SET ".$iuquery." WHERE lesson_type='I' AND tid=".$user->uid;	
				db_query($query);
			}
			
			if($Gqry != '') {
				$query = "UPDATE teacher_available SET ".$guquery." WHERE lesson_type='G' AND tid=".$user->uid;	
				db_query($query);
			}
		//}
		/*else 
		{
			for($i = 1; $i <= 7; $i++) {
			//Individual selected times
				if(isset($_POST['Individual_'.$i])) {
					 $Iqry[$i] = " {$day[($i-1)]} ";
					 $Ival[$i]= " '".$_POST['Individual_'.$i]."' ";				
					 
					 
				}
				//Group selected times
				if(isset($_POST['Group_'.$i])) {
					$Gqry[$i]= " {$day[($i-1)]} ";
					$Gval[$i]= " '".$_POST['Group_'.$i]."' ";
					
				}
			}
			$Iiqry=implode(",", $Iqry);
			$Iival=implode(",", $Ival);
			
			$Giqry=implode(",", $Gqry);
			$Gival=implode(",", $Gval);
			if($Iival!="")
			{
				db_query("insert into teacher_available(".$Iiqry.") values (".$Iival.")");
			}
			if($Gival!="")
			{
				db_query("insert into teacher_available(".$Giqry.") values (".$Gival.")");
			}
			
		}*/
		//Subjects, individual & group lessions price
		if($_POST['subject1'] != 0)
		$subject1=$_POST['subject1'];
		if($_POST['subject2'] != 0)
		$subject2=$_POST['subject2'];
		if($_POST['subject3'] != 0)
		$subject3=$_POST['subject3'];
		
	   $udquery = "UPDATE teacher_lession_prices SET subject1 ='$subject1',subject2 ='$subject2',subject3 ='$subject3',price_of_30min='".$_POST['price_of_30min']."',price_of_45min='".$_POST['price_of_45min']."',group_lesson_45min='".$_POST['group_lesson_45min']."',free_trail_lession='0' WHERE tid=".$user->uid;
		
		db_query($udquery);


	$updquery = "UPDATE teacher_discount_packages SET 10_less_30min_discount='".$_POST['10_less_30min_discount'].
	"',10_less_45min_discount='".$_POST['10_less_45min_discount']."',30_less_30min_discount='".$_POST['30_less_30min_discount']."',30_less_45min_discount='".$_POST['30_less_45min_discount']."',50_less_30min_discount='".$_POST['50_less_30min_discount'].
	"',50_less_45min_discount='".$_POST['50_less_45min_discount']."',100_less_30min_discount='".$_POST['100_less_30min_discount']."', 100_less_45min_discount='".$_POST['100_less_45min_discount']."' WHERE tid=".$user->uid;

	
		db_query($updquery);
		drupal_set_message('Details updated successfully');
		drupal_goto('edit_package');
		
	}else{	
	 $qry = "SELECT teacher_lession_prices.*,teacher_discount_packages.*,teacher_available.* FROM `teacher_lession_prices` LEFT JOIN teacher_discount_packages ON teacher_lession_prices.tid = teacher_discount_packages.tid INNER JOIN teacher_available ON teacher_lession_prices.tid=teacher_available.tid WHERE teacher_lession_prices.tid=".$user->uid;
	$res = db_query($qry);
	return theme('ulearn_edit_package', $res);
	}
}

function ulearn_student_booking () {
	global $user;		
	$_SESSION['tid']=$_REQUEST['uid'];		
	return theme('ulearn_student_booking', $res);
}

function ulearn_purchase_package(){
	return theme('ulearn_purchase_package', $res);
}

/**
* fn for inserting selected package data
* return true
*/
function ulearn_proceed_payment () {
	global $user;
	
	if(isset($_SESSION['lesson_type']) && $_SESSION['lesson_type']!='')
	{
		$qry="INSERT INTO student_booking 
		(
			`book_id`, 
			`sid`, 
			`lesson_type`, 
			`tid`, 
			`subject_id`, 
			`booked_on_date`, 
			`from_hrs`, 
			`from_mins`, 
			`to_hrs`, 
			`to_mins`, 
			`duration_mins`, 
			`package_id`, 
			`paid`, 
			`date_of_purchase`,
			`p_amount`,
			`modified`
		) 
		VALUES 
		(
			NULL, 
			'".$user->uid."', 
			'".$_SESSION['lesson_type']."', 
			'".$_SESSION['tid']."', 
			'".$_SESSION['subject_list']."', 
			'".$_SESSION['booked_on_date']."', 
			'".$_SESSION['hour']."', 
			'".$_SESSION['mins']."', 
			'".$_SESSION['to_hrs']."', 
			'".$_SESSION['to_mins']."', 
			'".$_SESSION['duration_mins']."', 
			'".$_SESSION['package_id']."', 
			'0', 
			CURRENT_TIMESTAMP,
			'".$_REQUEST ['p_amount']."',
			CURRENT_TIMESTAMP
		);";	
		
		db_query($qry);
		$_SESSION['paymentid'] = db_last_insert_id('student_booking', 'book_id');
	}
	/*
	if ( $_SESSION['paymentid'] ) {
		$res = db_fetch_object ( db_query("SELECT * FROM {packages_payment} WHERE id = " . $_SESSION['paymentid']));
	}
	if (empty ($res->id) || ($_SESSION['paymentid'] == $res->id) ) {
		$date_time = date('Y-m-d H:i:s');
		$sql = db_insert('packages_payment',
		array(
		'userid' => $user->uid,
		'p_id' => $_REQUEST['p_id'],
		'pt_id' => $_REQUEST['pt_id'],
		'p_amount' => $_REQUEST ['p_amount'],
		'paid' => '0',
		'created' => $date_time,
		'modified' => $date_time,
		)
		);
		$_SESSION['paymentid'] = db_last_insert_id('packages_payment', 'id');
	}
	*/
	echo 'Please wait you will redirect to PayPal in few seconds...';
}

function ulearn_success_payment () {
	global $base_url;
	$date_time = date('Y-m-d H:i:s');

	$qry = "UPDATE student_booking set txn_id='".$_REQUEST ['txn_id']."', paid='1', modified='".$date_time."' WHERE book_id='".$_SESSION['paymentid']."'";
	
	db_query($qry);
			
	unset ($_SESSION['paymentid']);
	unset ($_SESSION['lesson_type']);
	
	
	drupal_set_message('Your payment has been successfully done. Thanks for choosing this package.');
	//drupal_goto( $base_url . '/student_booking?uid='.$_SESSION['tid']);
	drupal_goto( $base_url . '/purchase_done');
			
	/*
	$sql = db_update('packages_payment',
	array(
	'txn_id' => $_REQUEST ['txn_id'],
	'paid' => '1',
	'modified' => $date_time,
	),
	$where
	);
	$res = db_fetch_object ( db_query("SELECT * FROM {packages_payment} WHERE id = " . $_SESSION['paymentid']));

	unset ($_SESSION['paymentid']);
	drupal_set_message('Your payment has been successfully done. Thanks for choosing this package.');
	drupal_goto( $base_url . '/view_package?lid=' . $res->p_id );
	*/
}

function ulearn_cancel_payment () {
	global $base_url;
	
	$date_time = date('Y-m-d H:i:s');
	
	$qry = "UPDATE student_booking set txn_id='".$_REQUEST ['txn_id']."', paid='2', modified='".$date_time."' WHERE book_id='".$_SESSION['paymentid']."'";
	
	db_query($qry);
			
	unset ($_SESSION['paymentid']);
	drupal_set_message('You are cancelled the payment transaction. Please verify once.');
	drupal_goto( $base_url . '/student_booking?uid='.$_SESSION['tid']);
	
	/*
	$date_time = date('Y-m-d H:i:s');
	$where['id'] = $_SESSION['paymentid'];
	$sql = db_update('packages_payment',
	array(
	'paid' => '2',
	'modified' => $date_time,
	),
	$where
	);
	$res = db_fetch_object ( db_query("SELECT * FROM {packages_payment} WHERE id = " . $_SESSION['paymentid']));
	unset ($_SESSION['paymentid']);
	drupal_set_message('You are cancelled the payment transaction. Please verify once.');
	drupal_goto( $base_url . '/view_package?lid=' . $res->p_id );
	*/
	
}

function ulearn_purchase_done(){
	
	global $base_url;
	$qry = "SELECT picture FROM {users} WHERE uid=".$_SESSION['tid'];
	$result = db_query($qry);
	$res = db_fetch_array($result);

	return theme('ulearn_purchase_done', $res);
}


?>
