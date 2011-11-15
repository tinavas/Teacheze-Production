
<?php
    drupal_add_js('misc/datepicker/js/jquery-1.3.2.min.js', 'core', 'header');
    drupal_add_js('misc/datepicker/js/jquery-ui-1.7.1.custom.min.js', 'core', 'header');	
	drupal_add_css('misc/datepicker/css/jquery-ui-1.7.1.custom.css', 'core', 'all');	
	

	/************************************************/
	//	After clicking the "Add to Checkout" button	//
	/************************************************/
	if(isset($_POST['lesson_type']))
	{
		//echo "<pre>"; print_r($_POST); echo "</pre>";exit;
		if($_POST['lesson_type'] == "I")
		{
			$duration_mins = $_POST['individual_mins'];
			$package_id = 0;
			
/*			$result=is_Teacher_Available($_POST['tid'], $_POST['hour'], $_POST['mins'], $duration_mins, $_POST['lesson_type'] , $_POST['datepicker']);
			
			//echo "REsult: ";var_dump($result);
						
			if($result)
				$errMsg=true;
			else
				$errMsg=false;*/
		}
		else
		if($_POST['lesson_type']=="G")
		{
			$duration_mins=$_POST['group_45mins'];
			$package_id=0;
			//echo is_Teacher_Available($_POST['tid'], $_POST['hour'], $_POST['mins'], $duration_mins, $_POST['lesson_type'] , $_POST['datepicker']);
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
		//	checks the Teacher available or not at our selected date & time 
		//********************************************************************\\
		$teacher_Available=is_Teacher_Available($_POST['tid'], $_POST['hour'], $_POST['mins'], $duration_mins, $_POST['lesson_type'] , $_POST['datepicker']);
			
		if($teacher_Available)
			$errMsg=true;
		else
			$errMsg=false;			
		
		$teacher_Booked=false;	
		if($teacher_Available)
		{
			//*****************************************\\
			//	Checking any Package is booked or not  \\
			//*****************************************\\
			
			$cdate=explode("/",$_POST['datepicker']);
			
			$dd=$cdate[0];
			$mm=$cdate[1];
			$yyyy=$cdate[2];
			
			$tStamp=mktime(0,0,0,$dd,$mm,$yyyy);
			
			
			//******************************************************************\\
			//	Checking the current cell time is booked by any Student or Not. \\
			//******************************************************************\\
			/*$q="select * from student_booking where tid='".$_POST['tid']."' 
				AND booked_on_date='".$yyyy."-".$dd."-".$mm."' 
				AND ( from_hrs>='".$_POST['hour']."' OR  to_hrs<='".$_POST['hour']."' )
				AND package_id=0";*/
			$endLesson=date('G:i',strtotime("+".$duration_mins." minutes",strtotime($_POST['hour'].":".$_POST['mins']."")));
				
			$q="select * from student_booking where 
				tid='".$_POST['tid']."' 
				AND booked_on_date='".$yyyy."-".$dd."-".$mm."' 
				AND
				(
					concat(from_hrs,':',from_mins)>='".$_POST['hour'].":".$_POST['mins']."'
						AND
					concat(from_hrs,':',from_mins)<='".$endLesson."'
				OR
					concat(to_hrs,':',to_mins)>='".$_POST['hour'].":".$_POST['mins']."'
						AND
					concat(to_hrs,':',to_mins)<='".$endLesson."'
				)";	
				
			echo $q."<br/>";
			
			$qr=db_query($q);
			$notBooked=0;
			
			while($rec=db_fetch_array($qr))
			{
					$errMsg=false;
					$teacher_Booked=true;
					
/*					//echo "<pre>";print_r($rec);
					//echo $rec['duration_mins']." <= ".$mins[$j][1];
					if($rec['from_hrs']!=NULL)
					{
						$mins=array('00',15,30,45);
						
						echo "FROM HRS:";
						var_dump($rec['from_hrs']);
						var_dump($mins);
						
						$hr=$rec['from_hrs'];
						$min=$rec['from_mins'];
						
						for($i=0;$i<count($mins);$i++)
						{
							
							//using "from hr, from mins" & Duration fields getting the "to hr, to mins" 
							$thisPosTime=$_POST['hour'].":".$mins[$i]."";
							
							//"HH:MM"
							$endLesson=date('G:i',strtotime("+0 minutes",strtotime($rec['to_hrs'].":".$rec['to_mins']."")));
							echo "<br/><br/>";							
							echo "This pos time: ".$thisPosTime.", EndLession: ".$endLesson."<br/>";							
							echo "IF Condition 1 : ".$thisPosTime.">=".$hr.":".$min."<br/>";
							var_dump($thisPosTime>=$hr.":".$min."");
							echo "<br/>";
							var_dump($thisPosTime<=$endLesson);
							echo "<br/><br/><br/>";
														
							//if($rec['from_hrs']==$j && $mins[$i]<=$rec['duration_mins'])
							if($thisPosTime>=$hr.":".$min."" && $thisPosTime<=$endLesson)
							{
								$bg=0;
	
								// Individual lesson booked
								if($rec['lesson_type']=='I')
									$bg='#0066FF';
								else 
								// Group lesson booked
								if($rec['lesson_type']=='G')					
									$bg='#00CCFF';
									
								echo "<div style='background:".$bg.";'>";
								echo "Booked..";
								echo "</div>";
								
								//if any student booked this time then increase the count
								$notBooked++;
							}
							
						}//end of for
					}*/
			}//while close			
						
			
		}// if $result close
		
		$dt=strtotime($_POST['datepicker']);
		$booked_on_date=date("Y-m-d",$dt);

		$to_hrs=date('G',strtotime("+".$duration_mins." minutes",strtotime($_POST['hour'].":".$_POST['mins']."")));
		$to_mins=date('i',strtotime("+".$duration_mins." minutes",strtotime($_POST['hour'].":".$_POST['mins']."")));
		
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
			`package_duration`, 
			`date_of_purchase`
		) 
		VALUES 
		(
			NULL, 
			'".$_POST['sid']."', 
			'".$_POST['lesson_type']."', 
			'".$_POST['tid']."', 
			'".$_POST['subject_list']."', 
			'".$booked_on_date."', 
			'".$_POST['hour']."', 
			'".$_POST['mins']."', 
			'".$to_hrs."', 
			'".$to_mins."', 
			'".$duration_mins."', 
			'".$package_id."', 
			'', 
			CURRENT_TIMESTAMP
		);";
		
		$r=db_query($qry);
		if($r)
			echo "<b>Saved...</b>";
		else
			echo "<i>Not Saved..</i>";
	}

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
//$tid=$_SESSION['tid'];
$tid = 100;
$sid=15;
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
	
	//Displays the selected Individual & Group available timming table.
	function display_schedule(){
	
		var msg='<table border=1>';
		msg+='<tr><td>&nbsp;</td>';
	
		for(i=0;i<day.length;i++)
			msg+='<td>'+day[i]+'</td>';
			
		msg+='</tr>';
		msg+='<tr><td>Individual</td>';
		
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
		
		msg+='<tr><td>Group</td>';
	
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
			tot_price=parseInt(price_30mins)*parseInt(pack_val);
			discount_price=(tot_price*packages_array[pack_val][0])/100;
			sub_total=Math.floor(tot_price-discount_price);
			document.getElementById('each_lesson_price').innerHTML=(sub_total/pack_val);
			document.getElementById('package_total').innerHTML=sub_total+".00";
			document.getElementById('package_save').innerHTML="&nbsp;&nbsp;&nbsp;&nbsp;("+packages_array[pack_val][0]+"% SAVINGS!) ";
		}
		if(document.booking_frm.package_mins[1].checked)
		{
			tot_price=parseInt(price_45mins)*parseInt(pack_val);
			discount_price=(tot_price*packages_array[pack_val][0])/100;
			sub_total=Math.floor(tot_price-discount_price);
			document.getElementById('each_lesson_price').innerHTML=(sub_total/pack_val);		
			document.getElementById('package_total').innerHTML=sub_total+".00";
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
	document.write('<div id="ajax_id" style="background-color:#F8F9E6"></div>');
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
			
				
		if(err!='')
		{
			alert(err);
			return;
		}
		document.booking_frm.lesson_type.value=ltype;
		//alert("U r : "+document.booking_frm.lesson_type.value);
		document.booking_frm.submit();
	}
	
</script>

<!--<input type="button" onclick="getSchedule('<?php //echo $tid;?>');" value="Click here.." />-->

<form action='' method='post' name='booking_frm'>

	<input type="hidden" name="tid" value="<?php echo $tid;?>" />
	<input type="hidden" name="sid" value="<?php echo $sid;?>" />	
	<input type="hidden" name="lesson_type" value="" />	
	
	
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
			<input type="text" name="datepicker" id="datepicker" onchange="getSchedule('<?php echo $_SESSION['tid'];?>');" >
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
					echo "<div style='background-color:green; width:300px;'>Available</div>";
				}
				else
				if(isset($errMsg) && $errMsg === false)				
				{
					echo "<div style='background-color:red; width:300px;'>Not Available</div>";
				}
				
			?>
		</td>
	</tr>
	</table>
	
	<div style="background-color:#CCC;">
	<h3>Buy an Individual lesson</h3>
		<table border="0" width="100%">
			<tr>
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
	
	<div style="background-color:#CCC;">
	<h3>OR, Buy a Group lesson</h3>
		<table border="0" width="100%">
			<tr>
				<td>
					<input type="checkbox" value="45" name="group_45mins" /> 
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
	<div style="background-color:#CCC;">
		<h3>OR, Buy a lesson Package with Discount</h3>
		<table width="100%">
			<tr>
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
	$r=db_query("SELECT * FROM  teacher_available WHERE tid='".$tid."' AND lesson_type='".$lesson_type."'");
	while($rec=db_fetch_object($r))
	{
		//echo $rec->tid."<br/>";	
		//echo $rec->lesson_type."<br/>";			
		switch($today_weekday)
		{
			case 1:
				echo $rec->Mon."<br/>";
				return is_Available($hour, $mins, $duration_mins, $rec->Mon, $tStamp);				
				break;
			case 2:
		
				echo $rec->Tue."<br/>";
				return is_Available($hour, $mins, $duration_mins, $rec->Tue, $tStamp);
				break;
				
			case 3:
				echo $rec->Wed."<br/>";			
				return is_Available($hour, $mins, $duration_mins, $rec->Wed, $tStamp);
				break;
			case 4:
				echo $rec->Thu."<br/>";			
				return is_Available($hour, $mins, $duration_mins, $rec->Thu, $tStamp);				
				break;
			case 5:
				echo $rec->Fri."<br/>";			
				return is_Available($hour, $mins, $duration_mins, $rec->Fri, $tStamp);				
				break;
			case 6:
				echo $rec->Sat."<br/>";			
				return is_Available($hour, $mins, $duration_mins, $rec->Sat, $tStamp);				
				break;
			case 7:
				echo $rec->Sun."<br/>";			
				return is_Available($hour, $mins, $duration_mins, $rec->Sun, $tStamp);				
				break;
		}
	}
	
	$qry="";
}

function is_Available($hour, $mins, $duration_mins, $i_day , $tStamp)
{
	//echo "Iday: "; var_dump($i_day);
	if($i_day === NULL || $i_day=='')
		return false;
	
	$time = str_pad((int)$hour,2,"0",STR_PAD_LEFT).":".str_pad((int)$mins,2,"0",STR_PAD_LEFT);
	
	$endTime=date('G:i',strtotime("+".$duration_mins." minutes",strtotime(str_pad((int)$hour,2,"0",STR_PAD_LEFT).":".$mins."")));
	$tt=explode(":",$endTime);
	$t=str_pad((int)$tt[0],2,"0",STR_PAD_LEFT);
	$endTime=$t.":".$tt[1];
	
	//echo "<br/>";
	//echo "Time: ".$time."<br/>";
	//echo "End Time: ".$endTime."<br/>";	
	
	//If multiple timming selected in one weekday then split the timming
	$sTime=explode(",",$i_day);
	//var_dump($sTime);
	
	$cou=0;
	for($a=0;$a<count($sTime);$a++)
	{
		
		//splitting the "from time" & "to Time" in to array
		$Available_Time=explode(" - ",$sTime[$a]);
		
		//echo "<br/>";
		//echo "Avail start time: ".(str_pad((int)$Available_Time[0],2,"0",STR_PAD_LEFT).":00")."<br/>";
		//echo "Avail End time: ".(str_pad((int)$Available_Time[1],2,"0",STR_PAD_LEFT).":45")."<br/>";						
		
		//echo "<br/> 1st Condition: ";
		//var_dump($time>=(str_pad((int)$Available_Time[0],2,"0",STR_PAD_LEFT).":00"));
		//echo "<br/> 2nd Condition: ";
		//var_dump($endTime<=(str_pad((int)$Available_Time[1],2,"0",STR_PAD_LEFT).":45"));
		//echo "<br/>";
		
		//if($time>=$Available_Time[0].":00" && $endTime<=$Available_Time[1].":45" )
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