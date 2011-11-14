<?php
session_start();
$con = mysql_connect("localhost","livedbuser","liveuser1@3") or die("db not connected..");
mysql_select_db("ulearn_livedb") or die("db not selected..");

	if(isset($_GET['date']) && $_GET['date']=="undefined")
	{
		//if date is not defined Pick tomorrow's date
		$theDate = date('m/d/Y');
		$timeStamp = StrToTime($theDate);
		$nextDay= StrToTime('+1 days', $timeStamp);
		//echo "{$theDate} + 1 days = ". date('Y-m-d', $in6days); 
		$_SESSION['date'] = date('m/d/Y', $nextDay);//date('m/d/Y');
		
	}
	else
	{
		if(isset($_GET['date']) && $_GET['date']!="undefined")
		{
			$_SESSION['date']=$_GET['date'];
		}
		else if(isset($_SESSION['date']))
		{
			$_SESSION['date']=$_SESSION['date'];
		}
		else
		{
			$_SESSION['date']=date("n/j/Y");
		}
		
	}
	if(isset($_GET['tid']))
	{
			$_SESSION['tid'] = $_GET['tid'];
	}
	$cdate=explode("/",$_SESSION['date']);
	$dd=$cdate[0];
	$mm=$cdate[1];
	$yyyy=$cdate[2];
	
	$tStamp=mktime(0,0,0,$dd,$mm,$yyyy);
	
	//Array used to display vertical minutes in booking table
	$mins=array('00',15,30,45);
?>

<table width="100%" border="0" cellpadding="2" style="border: 1px solid #999;">
	<tr>
		<td colspan="9" align="center">
			<table bgcolor="#99CC66">
				<tr>
					<td align="left">
						<!-- <input type="button" onclick="nextDay(<?php echo $_GET['tid']; ?>,-1);" value="Previous Day" name="previous"> -->
						<input type="image" src='<?php echo "sites/default/files/Previous.png"; ?>' alt="Next Day" width="50" onclick="nextDay(<?php echo $_GET['tid']; ?>,-1);" />
						
					</td>
					<td>		
						<h1 style="text-align:center; color:#FF9;">
							<?php echo date("l dS F Y",$tStamp); ?>
						</h1>
					</td>
					<td align="right">	
						<!-- <input type="button" onclick="nextDay(<?php echo $_GET['tid']; ?>,1);" value="Next Day" name="next"> -->
						<input type="image" src='<?php echo "sites/default/files/Next.png"; ?>' alt="Next Day" width="50" onclick="nextDay(<?php echo $_GET['tid']; ?>,1);" />
					</td>
				</tr>
			</table>		
		</td>
	</tr>
	<tr height="20px;">
		<td width="20px" bgcolor="#CCCCCC">&nbsp;</td>	
		<td>Not available</td>

		<td width="20px" bgcolor="#0066FF">&nbsp;</td>			
		<td>Individual Lesson booked</td>
		
		<td width="20px" bgcolor="#00CCFF">&nbsp;</td>					
		<td>Group Lesson booked</td>
	</tr>
	<tr>
		<td width="20px" bgcolor="#FFCC00">&nbsp;</td>			
		<td>Available for Individual & group lessons</td>

		<td width="20px" bgcolor="#FFFF00">&nbsp;</td>					
		<td>Available for Individual lessons only</td>
		
		<td width="20px" bgcolor="#00FF99">&nbsp;</td>					
		<td>Available for Group lessons Only </td>
	</tr>
	<tr>
		<td width="20px">&nbsp;</td>			
		<td>&nbsp;</td>

		<td width="20px" bgcolor="#FF0000">&nbsp;</td>					
		<td>Package Booked</td>
		
		<td width="20px" >&nbsp;</td>					
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td bgcolor="" colspan="9">

<?php
//*********************************************\\
//	Draw the Teacher Available table           \\ 
//*********************************************\\
echo "<table border=0 width='100%' cellpadding='2' style='border:1px solid #CCC;'>";
echo "<tr>";
	echo "<td>&nbsp;</td>";
	
	for($fr=0;$fr<24;$fr++)
	{
		echo "<td style='font-size:10px; color:#999;'>".$fr.":00</td>";	
	}
echo "</tr>";

for($i=0;$i<4;$i++)
{
	echo "<tr>";
	echo "<td style='font-size:10px; color:#999;'>".$mins[$i]."</td>";
	for($j=0;$j<24;$j++)
	{
		echo "<td align='center' style='border:1px solid #CCC;'>";
		
			global $mm,$dd,$yyyy;
			$mins[]=array(0,15,30,45);


			//*****************************************\\
			//	Checking any Package is booked or not  \\
			//*****************************************\\
			
			$packCount=0;
			$pkq="select * from student_booking where tid='".$_SESSION['tid']."' 
				AND (from_hrs='".$j."' OR  to_hrs='".$j."' ) 
				AND package_id!=0";
			
			$pkres=mysql_query($pkq);
			while($prec=mysql_fetch_assoc($pkres))
			{
				$currentDate=$tStamp;
				$package_Start_Date=strtotime($prec['booked_on_date']);
				
				
				$package_End_Date=strtotime(date("Y-m-d", $package_Start_Date) . " +10 day");
				
				if($currentDate>=$package_Start_Date && $currentDate<$package_End_Date)
				{
					if($prec['from_hrs']!=NULL)
					{
						
						$hr=$prec['from_hrs'];
						$min=$prec['from_mins'];
						
						//using "from hr, from mins" & Duration getting the "to hr, to mins" 
						$thisPosTime=$j.":".$mins[$i]."";
						
						//"HH:MM"
						$endLesson=date('G:i',strtotime("+0 minutes",strtotime($prec['to_hrs'].":".$prec['to_mins']."")));
						
						if($thisPosTime>=$hr.":".$min."" && $thisPosTime<=$endLesson)
						{
							echo "<div style='background:red;'>";
							echo "&nbsp;";
							echo "</div>";
							
							//if any student booked this time then increase the count
							++$packCount;
						}
					}
				}
			}// end While
			
			//**************************************************************************\\
			//	If package is booked then Ignore checking the Individual/Group plans	\\
			//**************************************************************************\\
			if($packCount==0)
			{
					//******************************************************************\\
					//	Checking the current cell time is booked by any Student or Not. \\
					//******************************************************************\\
					$q="select * from student_booking where tid='".$_SESSION['tid']."' 
						AND booked_on_date='".$yyyy."-".$dd."-".$mm."' 
						AND  ( from_hrs='".$j."' OR  to_hrs='".$j."' )
						AND package_id=0";
					
					$qr=mysql_query($q);
					$notBooked=0;
					while($rec=mysql_fetch_assoc($qr))
					{
							//echo $rec['duration_mins']." <= ".$mins[$j][1];
							if($rec['from_hrs']!=NULL)
							{
								
								$hr=$rec['from_hrs'];
								$min=$rec['from_mins'];
					
								//using "from hr, from mins" & Duration fields getting the "to hr, to mins" 
								$thisPosTime=$j.":".$mins[$i]."";
								
								//"HH:MM"
								$endLesson=date('G:i',strtotime("+0 minutes",strtotime($rec['to_hrs'].":".$rec['to_mins']."")));
												
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
									echo "&nbsp;";
									echo "</div>";
									
									//if any student booked this time then increase the count
									$notBooked++;
								}
							}
					}//while close
			}//If $packCount close
			
			//******************************************************************\\
			//	If no one is booked at this time then show Available.			\\
			//******************************************************************\\
			if($notBooked==0 && $packCount==0)
			{
				//calling the fn to check the INDIVIDUAL / GROUP availablity of Time. 1. fromHr, 2. mins array Index	
				$result=checkAvailableTime($j,$i);
				echo $result;
			}		

		echo "</td>";
	}
	echo "</tr>";
}
echo "</table>";

//main calender table close
echo "</td>
	</tr>
	</table>";


function isBooked($time){
	global $mm,$dd,$yyyy;
	 $q="select * from student_booking where tid='".$_SESSION['tid']."' 
			AND booked_on_date='".$yyyy."-".$dd."-".$mm."' 
			AND  from_hrs='".$time."'";
	//$r=mysql_fetch_assoc($q);
	//print_r($r);
}


//isAvailable(date("m/d/YYYY",$tStamp));
$individual_day[]=array();
$group_day[]=array();


//**************************************************************************\\
//	Function to check the teacher available for group/Individual lesson		\\
//**************************************************************************\\
function checkAvailableTime($time,$m ){
	
	//retrives the Teacher available timming in weekdays to Arrays
	readSchedule_fromDB();

	
	global $individual_day;
	global $group_day, $tStamp;
	global $vMins;
	$vMins=array(0,15,30,45);
	$today_weekday=date("N",$tStamp);

	$Group=0;
	$Indi=0;
	$found=0;
	//******************************************//
	//	Checking the Individual availability	\\
	//******************************************//	
	for($i=1;$i<=count($individual_day);$i++)
	{
		
		//if selected date weekday NUMBER is maching with date
		if($today_weekday==$i)
		{
			/*
			* For individual availability checking
			*/
			//if more than one schedule time available in a WeekDay			
			for($k=0;$k<count($individual_day[$i]);$k++)
			{
				
				//remove the Single quotes from time & to time eg: '2 - 3'
				$i_day=str_replace("'","",$individual_day[$i][$k]);
					
				if($i_day!='')
				{
					//If multiple timming selected in one weekday then split the timming
					$sTime=explode(",",$i_day);
					
					for($a=0;$a<count($sTime);$a++)
					{
						
						//splitting the "from time" & "to Time" in to array
						$locTime=explode("-",$sTime[$a]);
						
						if($time>=$locTime[0].":".$vMins[$m] && $time<=$locTime[1].":".$vMins[$m] )
						{
							$Indi++;
							$found++;								
						}
					}
				}
			}
		}
	}

	//**************************************
	//	Checking the Group availability
	//**************************************
	for($i=1;$i<=count($group_day);$i++)
	{	
		//echo $today_weekday." == ".$i;
		//if selected date weekday NUMBER is maching with date
		if($today_weekday==$i)
		{
	
			/*
			* For Group availability checking
			*/
			//if more than one schedule time available in a WeekDay			
			for($k=0;$k<count($group_day[$i]);$k++)
			{
				
				//remove the Single quotes from time & to time eg: '2 - 3'
				$i_day=str_replace("'","",$group_day[$i][$k]);
					
				if($i_day!='')
				{
					//If multiple timming selected in one weekday then split the timming
					$sTime=explode(",",$i_day);
					
					for($a=0;$a<count($sTime);$a++)
					{
						//splitting the from time & to Time in to array
						
						$locTime=explode("-",$sTime[$a]);
						//echo $locTime[0];
						//echo $locTime[1];
						
						if($time>=$locTime[0].":".$vMins[$m] && $time<=$locTime[1].":".$vMins[$m])
						{
							$Group++;
							$found++;								
						}
					}
									
				}
			}
		}
	}

	//Group & Individual lessons both are available
	if($Group>0 && $Indi>0)
	{
		echo "<div style='border:1px solid #FFF;background:#FFCC00;'>&nbsp;</div>";
	}
	else	
	if($Indi>0)
	{
		//Available for Individual lessions only
		echo "<div style='background:#FFFF00;'>";
		echo "&nbsp;";
		echo "</div>";
	}
	else
	if($Group>0)
	{
		//Available for Group lessions only		
		echo "<div style='background:#00FF99;'>";
		echo "&nbsp;";
		echo "</div>";
	}
	else
	//when Teacher is not available
	if($found==0)
	{
		echo "<div style='border:1px solid #FFF;background:#CCCCCC;'>&nbsp;</div>";
	}
}

//*******************************************************************************
//	Retrives the Teacher available timming & STORE in double dimension array's
//*******************************************************************************
function readSchedule_fromDB(){
	
	global $individual_day;
	global $group_day;

		
	$res1=mysql_query("select * from teacher_available where tid='".$_SESSION['tid']."'") or die('Qry Error');
	
	while($row=mysql_fetch_object($res1))
	{	
		if($row->lesson_type=='I')
		{
			$individual_day[1]=array(splitSchedule($row->Mon));
			$individual_day[2]=array(splitSchedule($row->Tue));
			$individual_day[3]=array(splitSchedule($row->Wed));
			$individual_day[4]=array(splitSchedule($row->Thu));
			$individual_day[5]=array(splitSchedule($row->Fri));
			$individual_day[6]=array(splitSchedule($row->Sat));
			$individual_day[7]=array(splitSchedule($row->Sun));
		}
		if($row->lesson_type=='G')
		{
			$group_day[1]=array(splitSchedule($row->Mon));
			$group_day[2]=array(splitSchedule($row->Tue));
			$group_day[3]=array(splitSchedule($row->Wed));
			$group_day[4]=array(splitSchedule($row->Thu));
			$group_day[5]=array(splitSchedule($row->Fri));
			$group_day[6]=array(splitSchedule($row->Sat));
			$group_day[7]=array(splitSchedule($row->Sun));
		}
	
	}	

}


//***********************************************************
//	When Teacher available in more than one time in a DAY, then 
//***********************************************************
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
