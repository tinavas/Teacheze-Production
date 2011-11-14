<?php
    drupal_add_js('misc/datepicker/js/jquery-1.3.2.min.js', 'core', 'header');
    drupal_add_js('misc/datepicker/js/jquery-ui-1.7.1.custom.min.js', 'core', 'header');	

	drupal_add_css('misc/datepicker/css/jquery-ui-1.7.1.custom.css', 'core', 'all');	
	
	$_SESSION['error_booking']='';
		/************************************************/
		//	After clicking the "Add to Checkout" button	//
		/************************************************/
		if(isset($_POST['lesson_type']))
		{
			$_SESSION['date']=$_POST['datepicker'];
			if($_POST['lesson_type'] == "I")
			{
				$duration_mins = $_POST['individual_mins'];
				$package_id = 0;
			}
			else
			if($_POST['lesson_type']=="G")
			{
				$duration_mins=$_POST['group_45mins'];
				$package_id=0;
			}
			else
			if($_POST['lesson_type']=="P")
			{
				if($_POST['package_mins']==0)
					$duration_mins=30;	
				else
					$duration_mins=45;		
				$package_id=$_POST['package_list'];
			}
			
			//********************************************************************\\
			//	checks the Teacher available or not at our selected date & time   \\
			//********************************************************************\\
			$teacher_Available=is_Teacher_Available($_POST['tid'], $_POST['hour'], $_POST['mins'], $duration_mins, $_POST['lesson_type'] , $_POST['datepicker']);
			
			if($teacher_Available)
				$errMsg=true;
			else
			{
				$errMsg=false;			
				$_SESSION['error_booking'].="Teacher is not Available at your selected Date/Time<br/>";
			}
			
			$teacher_Booked=false;	
			if($teacher_Available)
			{
				//*****************************************\\
				//	Checking any Package is booked or not  \\
				//*****************************************\\
				
				$cdate=explode("/",$_POST['datepicker']);
				
				$dd=$cdate[0]; //Month
				$mm=$cdate[1]; //Day 
				$yyyy=$cdate[2];
				
				$tStamp=mktime(0,0,0,$dd,$mm,$yyyy);
				
				
				//******************************************************************\\
				//	Checking the current cell time is booked by any Student or Not. \\
				//******************************************************************\\
				$endLesson=date('G:i',strtotime("+".$duration_mins." minutes",strtotime($_POST['hour'].":".$_POST['mins']."")));
					
				if($_POST['lesson_type']=='P')
					$package_lessons = $_POST['package_list'];
				else	
					$package_lessons = 0;
					
				// Query to chech any student already booked or not	
				$q="SELECT *, date_add(booked_on_date, INTERVAL package_id DAY) as booked_to_date FROM `student_booking` 
					WHERE 
					tid = '".$_POST['tid']."'
					AND 
					(
						(
							'".$yyyy."-".$dd."-".$mm."' >= booked_on_date 
							AND 
							'".$yyyy."-".$dd."-".$mm."' <= date_add(booked_on_date, INTERVAL package_id DAY) 
						)
						OR
						(
							date_add('".$yyyy."-".$dd."-".$mm."', INTERVAL ".$package_lessons." DAY) >= booked_on_date 
							AND 
							date_add('".$yyyy."-".$dd."-".$mm."', INTERVAL ".$package_lessons." DAY) <= date_add(booked_on_date, INTERVAL package_id DAY) 
					
						)
					)
					AND 
					( 
						( 
							'".$_POST['hour'].":".$_POST['mins']."' >= concat(from_hrs,':',from_mins) 
							AND 
							'".$_POST['hour'].":".$_POST['mins']."' <= concat(to_hrs,':',to_mins)
						) 
						OR 
						( 
							'".$endLesson."' >= concat(from_hrs,':',from_mins) 
							AND 
							'".$endLesson."' <= concat(to_hrs,':',to_mins) 
						)
					) 
				";				
				
				$qr=db_query($q);
				$notBooked=0;
				while($rec=db_fetch_array($qr))
				{
						$errMsg=false;
						$teacher_Booked=true;
				}//while close			
	
				if($teacher_Booked)
					$_SESSION['error_booking'].="Teacher booked by some other student<br/>";
				
				
			}// if $result close
			
			$dt=strtotime($_POST['datepicker']);
			$booked_on_date=date("Y-m-d",$dt);
	
			$to_hrs=date('G',strtotime("+".$duration_mins." minutes",strtotime($_POST['hour'].":".$_POST['mins']."")));
			$to_mins=date('i',strtotime("+".$duration_mins." minutes",strtotime($_POST['hour'].":".$_POST['mins']."")));
			
			/*exit;		*/
			

			
			//****************************************************************//
			//	If teacher is available & no one is booked then save to db	  //	
			//****************************************************************//

			//if(isset($errMsg) && $errMsg === true)
				//$r=db_query($qry);
			var_dump($_SESSION['error_booking']);	
			
			if($_SESSION['error_booking']=='')
			{
				$_SESSION['lesson_type']=$_POST['lesson_type'];
				$_SESSION['tid'] = $_POST['tid'];
				$_SESSION['subject_list'] = $_POST['subject_list'];
				$_SESSION['booked_on_date'] = $booked_on_date;
				$_SESSION['hour'] = $_POST['hour'];
				$_SESSION['mins'] = $_POST['mins'];
				$_SESSION['to_hrs'] = $to_hrs;
				$_SESSION['to_mins'] = $to_mins;
				$_SESSION['duration_mins'] = $duration_mins;
				$_SESSION['package_id'] = $package_id;
				
				//echo "<b>Saved...</b>";
				//$_SESSION['error_booking'].="<h2>Booked Successfully..</h2>";
				$_SESSION['error_booking']='';
				header("Location: ".url('purchase_package'));				
			}
			else
			{
				//echo "<i>Not Saved..</i>"; 
				$_SESSION['error_booking'].="<h2 style='color:red;'>Booking Not Saved.</h2>";			
			}

			
		}
	
	/********************************************/
	
		

?>

<!-- Date Picker  -->

	<script type="text/javascript">
		$(function() {
			$("#datepicker").datepicker({minDate: '+0M +1D', maxDate: '+1000M +10000D'});
		});
	</script>

<!-- End Of Date Picker  -->

<?php 

//mysql_connect("localhost","root","") or die("db not connected..");
//mysql_select_db("ulearn_test") or die("db not selected..");

//Teacher ID
$tid=$_GET['uid'];
//$sid=$_SESSION['sid'];
//$sid = $user->uid;
$_SESSION['tid']=$tid;

$res=db_query("select * from teacher_available where tid='".$tid."'");

$msg="";
	
while($row=db_fetch_object($res))
{	


	if($row->lesson_type=='I')
	{
		$msg.="individual_day[1]=new Array(".splitSchedule($row->Mon).");";
		$msg.="individual_day[2]=new Array(".splitSchedule($row->Tue).");";
		$msg.="individual_day[3]=new Array(".splitSchedule($row->Wed).");";
		$msg.="individual_day[4]=new Array(".splitSchedule($row->Thu).");";
		$msg.="individual_day[5]=new Array(".splitSchedule($row->Fri).");";
		$msg.="individual_day[6]=new Array(".splitSchedule($row->Sat).");";
		$msg.="individual_day[7]=new Array(".splitSchedule($row->Sun).");";
	}
	if($row->lesson_type=='G')
	{
		$msg.="group_day[1]=new Array(".splitSchedule($row->Mon).");";
		$msg.="group_day[2]=new Array(".splitSchedule($row->Tue).");";
		$msg.="group_day[3]=new Array(".splitSchedule($row->Wed).");";
		$msg.="group_day[4]=new Array(".splitSchedule($row->Thu).");";
		$msg.="group_day[5]=new Array(".splitSchedule($row->Fri).");";
		$msg.="group_day[6]=new Array(".splitSchedule($row->Sat).");";
		$msg.="group_day[7]=new Array(".splitSchedule($row->Sun).");";
	}

}	


function splitSchedule($arr){
	
	$time=explode(",", $arr);
	$val='';
	for($i=0;$i<count($time);$i++)
	{
			$val.="'".$time[$i]."', ";
	}
	//remove last comma & space
	$retu=substr($val,0,strlen($val)-2);	
	return $retu;	
}

?>
<!-- div used to display the Teacher available time table -->
<div id='sch_table'>
</div>

<script type="text/javascript">
	var individual_day=new Array();
	
	var group_day=new Array();
	
	
	<?php
	echo $msg;
	?>
	
	var day=new Array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	
	display_schedule();
	
	//sorts the array
	function sortNumber(a,b)
	{
		return a - b;
	}
	
	/****************************************************************************/
	//Displays the selected Teacher's Individual & Group available timming table. //
	/****************************************************************************/	
	function display_schedule(){
	
		var msg='<table border=1 bgcolor="#F8F9E6">';
		
		msg+='<tr bgcolor="#99CC66"><td  bgcolor="#FFFFFF">&nbsp;</td>';
	
		for(i=0;i<day.length;i++)
			msg+='<td>'+day[i]+'</td>';
			
		msg+='</tr>';
		msg+='<tr><td align="center"  bgcolor="#99CC66"> <img src="<?php echo url("sites/default/files/individual.png"); ?>" alt="Individual" width=30 /><br/>Individual</td>';
		
		for(i=1;i<individual_day.length;i++)
		{
			if(individual_day[i].length>0)
			{
				individual_day[i].sort();
				msg+='<td>';
				for(k=0;k<individual_day[i].length;k++)
				{
					if(individual_day[i][k]!='')
						msg+=individual_day[i][k]+"<br/>";
				}
				msg+='</td>';
			}
			else
				msg+='<td>&nbsp;</td>';
		}
		msg+='</tr>';
		
		msg+='<tr><td align="center"  bgcolor="#99CC66"> <img src="<?php echo url("sites/default/files/group.png"); ?>" alt="Group" width=40 /><br/>Group</td>';
	
		for(i=1;i<group_day.length;i++)
		{
			if(group_day[i].length>0)
			{
				group_day[i].sort();
				msg+='<td>';
				for(k=0;k<group_day[i].length;k++)
				{
					
					if(group_day[i][k]!='')
					msg+=group_day[i][k]+"<br/>";
				}
				msg+='</td>';
			}
			else
				msg+='<td>&nbsp;</td>';
		}
		
			
		msg+='</tr></table><br/>';
	
		document.getElementById('sch_table').innerHTML=msg;
	
	}
	
	var packages_array=new Array();
	//Display the package price when a user selects a plan & duration time
	
	function show_package_price(){
		
		//retrive the package value
		pack_val=document.getElementById('package_list').value;
		var tot_price,sub_total=0;

		if(document.booking_frm.package_mins[0].checked)
		{
			tot_price = parseInt(price_30mins)*parseInt(pack_val);
			discount_price = parseInt(tot_price)*parseInt(packages_array[pack_val][0])/100;
			sub_total = (tot_price-discount_price);

			document.getElementById('each_lesson_price').innerHTML=(sub_total/pack_val);
			document.getElementById('package_total').innerHTML=sub_total;
			document.getElementById('package_save').innerHTML="&nbsp;&nbsp;&nbsp;&nbsp;("+packages_array[pack_val][0]+"% SAVINGS!) ";
			//alert(parseInt(tot_price)+" * "+parseInt(packages_array[pack_val][0])+" / "+100);
		}
		if(document.booking_frm.package_mins[1].checked)
		{
			tot_price=parseInt(price_45mins)*parseInt(pack_val);
			discount_price = parseInt(tot_price)*parseInt(packages_array[pack_val][1])/100;
			sub_total=(tot_price-discount_price);
			
			document.getElementById('each_lesson_price').innerHTML=(sub_total/pack_val);		
			document.getElementById('package_total').innerHTML=sub_total;
			document.getElementById('package_save').innerHTML="&nbsp;&nbsp;&nbsp;&nbsp;("+packages_array[pack_val][1]+"% SAVINGS!) ";
		}

	
	}

	//Onchange calender date display Teacher Schedule	
	function getSchedule(tid)
	{
		var on_date= $('#datepicker').val();
	
		//If date is not selected then get current date
		if(on_date=='')
		{
			var d=new Date();
			var on_date=(d.getMonth()+1);
			on_date+=('/');
			on_date+=(d.getDate());
			on_date+=('/');
			on_date+=(d.getFullYear());
		}
		var url = '<?php echo url('sites/all/modules/ulearn/teacher_calender.php');?>?date='+on_date+'&tid='+tid;
		$('#ajax_id').load(url);
	
	}
	
	/***********************************************
	*	div used to display the Schedule CALENDER
	************************************************/
	</script>
	<!--	<table width="100%">
			<tr>
				<td>
					<input type="button" name="previous" value="Previous Day" onclick="nextDay(<?php echo $tid;?>,-1);"/>
				</td>
				<td align="right">
					<input type="button" name="next" value="Next Day" onclick="nextDay(<?php echo $tid;?>,1);"/>
				</td>
			</tr>
		</table> -->
		<div id="ajax_id" style="background-color:#F8F9E6"></div>
	
	<script>
	
	getSchedule('<?php echo $tid;?>');
	
	
	//On Add to Checkout clicked
	function addToCart(ltype){
		
		var err='';
		if(document.booking_frm.subject_list.value==0)
			err+="Select the Subject\n\r";
		if(document.booking_frm.datepicker.value=='')
			err+="Pick the date \n\r";
		if(document.booking_frm.hour.value==-1)
			err+="Pick the Hour \n\r";
		if(document.booking_frm.mins.value==-1)
			err+="Pick the Minutes \n\r";
		if(document.booking_frm.hour.value>=23 && document.booking_frm.mins.value>=30)	
			err+="Invalid Time \n\r";
			
		current_date=new Date();
		if(current_date >= new Date(document.getElementById('datepicker').value))	
			err+="You Cant pick Past date\n\r";			
		if(ltype=='G' && document.booking_frm.group_45mins.checked==false)
		{
			err+="Group Check box '45 Minutes' not selected.";
		}
			
		if(err!='')
		{
			alert(err);
			return;
		}
		document.booking_frm.lesson_type.value=ltype;
		//alert("U r : "+document.booking_frm.lesson_type.value);
		document.booking_frm.submit();
	}


	function nextDay(tid,nextORprev)
	{
		var on_date= $('#datepicker').val();
		if(on_date!='')
			var dd=on_date;
		else
		{
			var d=new Date();
			var dd=(d.getMonth()+1);
			dd+=('/');
			dd+=(d.getDate());
			dd+=('/');
			dd+=(d.getFullYear());
			//alert(dd);
		}
		dt=dd.split("/");
		var d = dt[0];
		var m = dt[1];
		var y = dt[2];
			
		var d = new Date(y,d,m);
		if(nextORprev==1)
			d.setDate(d.getDate()+1);
		else
			d.setDate(d.getDate()-1);					
		ndate='';
		ndate+=(d.getMonth())+"/";
		ndate+=(d.getDate())+"/";
		ndate+=(d.getFullYear());
		document.getElementById('date').value=ndate;   
		document.getElementById('datepicker').value=ndate;
		//alert(ndate);

		var url = '<?php echo url('sites/all/modules/ulearn/teacher_calender.php');?>?date='+ndate+'&tid='+tid;
		$('#ajax_id').load(url);
		
		//document.cal_frm.submit();
	}
</script>


<form action='' method='post' name='booking_frm'>

	<input type="hidden" name="tid" value="<?php echo $tid;?>" />
	<input type="hidden" name="sid" value="<?php echo $user->uid;?>" />	
	<input type="hidden" name="lesson_type" value="" />	
 	<input type="hidden" name="date" id="date" value="<?php echo $_SESSION['date']; ?>" />	
	<br />
	<?php
	
	$sq=db_query("SELECT tl.*, ts.sub_id, ts.subject FROM `teacher_lession_prices` tl, teacher_subjects_master ts
	where (
	tl.subject1=ts.sub_id OR 
	tl.subject2=ts.sub_id OR 
	tl.subject3=ts.sub_id ) AND tl.tid='".$tid."'");	
	
	
	$sub_list='<option value="0">Select Subject</option>';
	while($row=db_fetch_object($sq))
	{	
		$sub_list.="<option value='".$row->sub_id."'>".$row->subject."</option>";
	}	
	echo "Select Subject <select name='subject_list' id='subject_list'>";
	echo $sub_list;
	echo "</select>";	
	
	
	//retrives teacher lesson prices
	$res=db_query("select * from teacher_lession_prices WHERE tid='".$tid."' LIMIT 1");
	$pr=db_fetch_object($res);
	
	echo "<script>var price_30mins='".$pr->price_of_30min."';</script>";
	echo "<script>var price_45mins='".$pr->price_of_45min."';</script>";
	
	//retrives packages discount values
	$pkres=db_query("SELECT * FROM `teacher_discount_packages` WHERE tid='".$tid."' LIMIT 1"); 
	$pk=db_fetch_array($pkres);
	
	$pk_msg='';
	$pk_msg.="packages_array[10]=new Array('".$pk['10_less_30min_discount']."', '".$pk['10_less_45min_discount']."');"; 
	
	$pk_msg.="packages_array[30]=new Array('".$pk['30_less_30min_discount']."', '".$pk['30_less_45min_discount']."');"; 
	
	$pk_msg.="packages_array[50]=new Array('".$pk['50_less_30min_discount']."', '".$pk['50_less_45min_discount']."');";
	
	$pk_msg.="packages_array[100]=new Array('".$pk['100_less_30min_discount']."', '".$pk['100_less_45min_discount']."');";
	
	echo "<script>".$pk_msg."</script>";
	
	?>
	<br />
	<table width="100%">
	<tr>
		<td>
			Date : 	
			<input type="text" name="datepicker" id="datepicker" value="<?php echo $_SESSION['date']; ?>" onchange="getSchedule('<?php echo $tid;?>');" >
		</td>
		<td>
			Time : 
			<select name='hour' id='hour' style='width:50px'>
					<option value="-1">Hr</option>
				<?php
				for($i=0;$i<24;$i++)
					echo "<option value='".$i."'>{$i}</option>\n";
				?>
			</select>:<select name="mins" id='mins' style='width:55px'>
				<option value="-1">Mins</option>			
				<option value="0">00</option>
				<option value="15">15</option>		
				<option value="30">30</option>		
				<option value="45">45</option>
			</select>	
		</td>
		<td width="300px">
			<?php
				if(isset($errMsg) && $errMsg === true)
				{
					echo "<div style='background-color:#99FF99; width:300px;border:2px solid green;'>".$_SESSION['error_booking']."</div>";
				}
				else
				if(isset($errMsg) && $errMsg === false)				
				{
					echo "<div style='background-color:#FF9933; width:300px; border:2px solid red;'>".$_SESSION['error_booking']."</div>";
				}
				
			?>
		</td>
	</tr>
	</table>
	
	<div style="background-color:#F8F9E6;">
	<h3>Buy an Individual lesson</h3>
		<table border="0" width="100%">
			<tr>
				<td width="80">
				<img src="<?php echo url("sites/default/files/individual.png"); ?>" alt="Individual" width="50" />
				</td>
				<td>
					<input type="radio" value="30" name="individual_mins" checked="checked" /> 
					30 Minutes 
						<b> 
							€ <?php echo $pr->price_of_30min; ?>
						</b>
					<br/>
					<input type="radio" value="45" name="individual_mins" /> 
					45 Minutes 
					<b> 
						€ <?php echo $pr->price_of_45min; ?>
					</b>
		
				</td>
				<td align="right" valign="bottom">
					<input type="button" value="Add to Checkout" onclick="addToCart('I');"/>
				</td>
			</tr>
		</table>		
	</div>
	
	<div style="background-color:#F8F9E6;">
	<h3>OR, Buy a Group lesson</h3>
		<table border="0" width="100%">
			<tr>
				<td width="80">
				<img src="<?php echo url("sites/default/files/group.png"); ?>" alt="Group" width="50" />
				</td>

				<td>
					<input type="checkbox" value="45" name="group_45mins" id="group_45mins" checked="checked" /> 
					45 Minutes 
						<b> 
							€ <?php echo $pr->group_lesson_45min; ?>
						</b>
				</td>
				<td align="right" valign="bottom">
					<input type="button" value="Add to Checkout" onclick="addToCart('G');"/>
				</td>
			</tr>
		</table>		
	</div>
	<br/>
	<div style="background-color:#F8F9E6;">
		<h3>OR, Buy an Individual Lesson Package with Discount</h3>
		<table width="100%">
			<tr>
				<td width="80">
				<img src="<?php echo url("sites/default/files/package.png"); ?>" alt="Package" width="50" />
				</td>
				<td>
					Number of lessons : 
						<select name='package_list' id='package_list' onchange="show_package_price();" >
							<option value='10'>10 lessons</option>
							<option value="30">30 lessons</option>
							<option value="50">50 lessons</option>
							<option value="100">100 lessons</option>
						</select>
					<br/>
					Each lesson Price : <b> € <span id='each_lesson_price'></span></b>
					<br/>
					
						<input type="radio" value="0" name="package_mins" onclick="show_package_price();" checked="checked" /> 30 Minutes 
						<br/>
						<input type="radio" value="1" name="package_mins" onclick="show_package_price();" /> 45 Minutes 
					<br/>
					<b>Sub Total :  € <span id='package_total'></span></b>
					<b><span id="package_save" style="color:#F60; font-size:18px;"></span></b>
					<br/>
				</td>
				<td align="right" valign="bottom">
						<input type="button" value="Add to Checkout" onclick="addToCart('P');"/>
				</td>
			</tr>
		</table>		
	</div>		
</form>
<script language="javascript">
	//Display Packages Each lesson price, Sub Total & %Savings
	show_package_price();
</script>

<?php
/*********** Srinivas fns ******************/

function is_Teacher_Available($tid, $hour, $mins, $duration_mins , $lesson_type , $datepicker)
{
	$cdate=explode("/",$datepicker);
	$mm=$cdate[0];
	$dd=$cdate[1];
	$yyyy=$cdate[2];
	$tStamp=mktime(0,0,0,$mm,$dd,$yyyy);
	//echo date("m-d-Y",$tStamp)."<br/>";
	$today_weekday = date("N",$tStamp);
	
	//echo "Weekday: ".$today_weekday;
	//echo "<br/>";
	
	if($lesson_type=='P')
	{
		
		// When package is selected check availability of teacher as INDIVIDUAL schedule//
		$r=db_query("SELECT * FROM  teacher_available WHERE tid='".$tid."'  AND lesson_type='I'");	
		
		$rec=db_fetch_object($r);
			//for packages teacher must available in all week
			if( ! ( 
					is_Available($hour, $mins, $duration_mins, $rec->Mon, $tStamp)
					||
					is_Available($hour, $mins, $duration_mins, $rec->Tue, $tStamp)
					||
					is_Available($hour, $mins, $duration_mins, $rec->Wed, $tStamp)
					||
					is_Available($hour, $mins, $duration_mins, $rec->Thu, $tStamp)
					||
					is_Available($hour, $mins, $duration_mins, $rec->Fri, $tStamp)
					||
					is_Available($hour, $mins, $duration_mins, $rec->Sat, $tStamp)
					||
					is_Available($hour, $mins, $duration_mins, $rec->Sun, $tStamp)
				   )	
			)
			{
				$_SESSION['error_booking'].="Teacher is not available in full weekday<br/>";
				return false;
			}
			
		$r=db_query("SELECT * FROM  teacher_available WHERE tid='".$tid."'  AND lesson_type='I'");				
		
	}
	else
		$r=db_query("SELECT * FROM  teacher_available WHERE tid='".$tid."' AND lesson_type='".$lesson_type."'");

		
	while($rec=db_fetch_object($r))
	{
	//echo $rec->tid."<br/>";	
		//echo $rec->lesson_type."<br/>";			
		switch($today_weekday)
		{
			case 1:
				//echo $rec->Mon."<br/>";
				if($rec->Mon!=NULL)
					return is_Available($hour, $mins, $duration_mins, $rec->Mon, $tStamp);				
				break;
			case 2:
		
				//echo $rec->Tue."<br/>";
				if($rec->Tue!=NULL)				
					return is_Available($hour, $mins, $duration_mins, $rec->Tue, $tStamp);
				break;
				
			case 3:
				//echo $rec->Wed."<br/>";			
				if($rec->Wed!=NULL)				
					return is_Available($hour, $mins, $duration_mins, $rec->Wed, $tStamp);
				break;
			case 4:
				//echo $rec->Thu."<br/>";			
				if($rec->Thu!=NULL)				
					return is_Available($hour, $mins, $duration_mins, $rec->Thu, $tStamp);				
				break;
			case 5:
				//echo $rec->Fri."<br/>";			
				if($rec->Fri!=NULL)				
					return is_Available($hour, $mins, $duration_mins, $rec->Fri, $tStamp);				
				break;
			case 6:
				//echo $rec->Sat."<br/>";			
				if($rec->Sat!=NULL)				
					return is_Available($hour, $mins, $duration_mins, $rec->Sat, $tStamp);				
				break;
			case 7:
				//echo $rec->Sun."<br/>";			
				if($rec->Sun!=NULL)				
					return is_Available($hour, $mins, $duration_mins, $rec->Sun, $tStamp);				
				break;
		}
	}
	
	//$qry="";
}

function is_Available($hour, $mins, $duration_mins, $i_day , $tStamp)
{
	if($i_day === NULL || $i_day=='')
		return false;
	
	$time = str_pad((int)$hour,2,"0",STR_PAD_LEFT).":".str_pad((int)$mins,2,"0",STR_PAD_LEFT);
	
	$endTime=date('G:i',strtotime("+".$duration_mins." minutes",strtotime(str_pad((int)$hour,2,"0",STR_PAD_LEFT).":".$mins."")));
	$tt=explode(":",$endTime);
	$t=str_pad((int)$tt[0],2,"0",STR_PAD_LEFT);
	$endTime=$t.":".$tt[1];
	
	//If multiple timming selected in one weekday then split the timming
	$sTime=explode(",",$i_day);
	
	$cou=0;
	for($a=0;$a<count($sTime);$a++)
	{
		//splitting the "from time" & "to Time" in to array
		$Available_Time=explode(" - ",$sTime[$a]);
		
		if($time >= str_pad((int)$Available_Time[0],2,"0",STR_PAD_LEFT).":00" 
			&& 
			$endTime <= str_pad((int)$Available_Time[1],2,"0",STR_PAD_LEFT).":45" 
			)
		{
			$cou++;
		}
	}
	
	if($cou>0)
		return true;				
	else
		return false;	

}

?>