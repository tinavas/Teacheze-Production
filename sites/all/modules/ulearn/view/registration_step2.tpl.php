<?php
global $user;
?>

<div id='sch_table'></div>
<script>

var individual_day=new Array();

individual_day[1]=new Array();
individual_day[2]=new Array();
individual_day[3]=new Array();
individual_day[4]=new Array();
individual_day[5]=new Array();
individual_day[6]=new Array();
individual_day[7]=new Array();

var group_day=new Array();

group_day[1]=new Array();
group_day[2]=new Array();
group_day[3]=new Array();
group_day[4]=new Array();
group_day[5]=new Array();
group_day[6]=new Array();
group_day[7]=new Array();

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
				//alert(individual_day[i][k]+ ", "+i+" - "+k);
				msg+=individual_day[i][k]+" <img src='misc/delete.jpg' style='cursor:pointer;' onclick='delete_sch(\"I\","+i+","+k+");' title='"+individual_day[i][k]+"'/><br/>";
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
				//alert(group_day[i][k]+ ", "+i+" - "+k);
				msg+=group_day[i][k]+" <img src='misc/delete.jpg' style='cursor:pointer;' onclick='delete_sch(\"G\","+i+","+k+");' title='"+group_day[i][k]+"'/><br/>";
			}
			msg+='</td>';
		}
		else
			msg+='<td>&nbsp;</td>';
	}
	
		
	msg+='</tr></table><br/>';

	document.getElementById('sch_table').innerHTML=msg;

}


//Onclick Add button add's the time to the array
function addSchedule(){

	individual=document.sch_entry_frm.ltype[0].checked;
	
	day_val=document.getElementById('day').value;
	
	fhr=document.getElementById('fhour').value;
	//fmin=document.getElementById('fmin').value;
	
	thr=document.getElementById('thour').value;
	//tmin=document.getElementById('tmin').value;
	//div=fhr+":"+fmin+" - "+thr+":"+tmin;
	div=fhr+" - "+thr;
	
	
	//When Entireday checked
	if(document.sch_entry_frm.eday.checked)
	{
		div="8 - 21";
		//individual_day[day_val].splice(0,individual_day[day_val].length,div);
		//display_schedule();
		//return;
	}
	else
	if((thr==0 && fhr==0) || parseInt(fhr)>=parseInt(thr))
		return;
	
	
	if(individual)
	{
		
		if(div=="8 - 21")
			individual_day[day_val].splice(0,individual_day[day_val].length,div);
		else		
		//search time already added or not in INDIVIDUAL type
		if(find_schedule(individual_day[day_val],div))
			return;
		else
			individual_day[day_val].push(div);
		
			
	}
	else
	{
		if(div=="8 - 21")
			group_day[day_val].splice(0,group_day[day_val].length,div);
		else
		//search time already added or not in GROUP type
		if(find_schedule(group_day[day_val],div))
			return;
		else
			group_day[day_val].push(div);

	}
	
	//display's Individual & Group available timing in tabular format
	display_schedule();
	
	
	//Uncheck the EntireDay check box after adding
	if(document.sch_entry_frm.eday.checked)
	{
		document.sch_entry_frm.eday.checked=false;
		disableElements();
	}	
}

//Removs the available time from Array
function delete_sch(lessType,i,k){
	
	//alert(lessType+" : "+i+" - "+k );
	
	if(lessType=='I')
	{
		individual_day[i].splice(k,1)	
	}
	else
	{
		group_day[i].splice(k,1);
	}
	display_schedule();	
}


//On click save button creates Input hidden box's, to transfer selected timing to db
function saveSchedule(){
	
	
	//Individual
	for(i=1;i<individual_day.length;i++)
	{
		if(individual_day[i].length>0)
		{
			//individual_day[i].sort();
			for(k=0;k<individual_day[i].length;k++)
			{
					document.getElementById('Individual_div').innerHTML+="Individual_"+day[(i-1)]+": <input type='text' name='Individual_"+i+"' value='"+individual_day[i]+"'/><br/>";
			}
		}
	}	
	
	//Group 
	for(i=1;i<group_day.length;i++)
	{
		if(group_day[i].length>0)
		{
			for(k=0;k<group_day[i].length;k++)
			{
					document.getElementById('Group_div').innerHTML+="Group_"+day[(i-1)]+": <input type='text' name='Group_"+i+"' value='"+group_day[i]+"'/><br/>";									
			}
		}
	}	
	//Submit the form
	//document.sch_entry_frm.submit();
	//document.sch_entry_frm.individual_txt.value=individual_day;
	return true;
}

//checks the available timing validation
function find_schedule(arr, v){

	for(i=0;i<arr.length;i++)
	{

		//check the new Time value already exist or not
		timearr=arr[i].split('-');
		varr=v.split('-');
		
		//selected time already exist OR entire day is exist
		if(arr[i]==v || arr[i]=='8 - 21')
			return true;
		else
		//selected time already exist then cancel adding
		if(timearr[0]==varr[0] || (parseInt(varr[0])>parseInt(timearr[0]) && parseInt(varr[0])<parseInt(timearr[1]))) 
		{
			return true;
		}
		else
		//if 5 - 8 is exist & checks new value 1 - 9. returns true
		if(parseInt(varr[0])<parseInt(timearr[0]) && parseInt(varr[1])>parseInt(timearr[1]))
			return true;
		else
		if(parseInt(varr[1])>parseInt(timearr[0]) && parseInt(varr[1])<=parseInt(timearr[1]))
			return true;
			
	}		
	
	return false;

}

//when Entire Day available checkbox is selected disable the from & to dropdowns
function disableElements(){

	if(document.sch_entry_frm.eday.checked)
	{
		document.getElementById('fhour').disabled=true;
		document.getElementById('thour').disabled=true;		
	}
	else
	{
		document.getElementById('fhour').disabled=false;
		document.getElementById('thour').disabled=false;		
	}

}

//Dynamically adding Subjects list boxes
var slist=1;
function addSubjectList(){
	if(slist<3)
	{
		slist++;
		document.getElementById('sub'+slist).style.display='block';
	}
}
//Dynamically Hiding Subjects list boxes
function hideList(listId){
	document.getElementById('sub'+listId).style.display='none';	
	slist--;
}

//discount packages
function disc(package,mins,percent){
	if(percent!='')
	percent=parseInt(percent);

	if(percent>=0 && percent<=10 && percent!='NaN')
	{

		div=package+"_less_"+mins+"min_disc_div";
		txt=package+"_less_"+mins+"min_disc_txt";

		if(parseInt(mins)==30)
		{
			elm = document.getElementById('price_of_30min');
			sele_price=(elm.options[elm.selectedIndex].value);
			sele_price=(elm.options[elm.selectedIndex].value);			
		}
		if(parseInt(mins)==45)
		{
			elm = document.getElementById('price_of_45min');
			sele_price=(elm.options[elm.selectedIndex].value);
			sele_price=(elm.options[elm.selectedIndex].value);			
		}
		

		
		tot=parseInt(package)*parseInt(sele_price);
		
		discount=(tot*percent)/100;
		tot_after_disc=Math.floor(tot-discount);
		
		document.getElementById(txt).value=tot_after_disc;		
		document.getElementById(div).innerHTML=tot_after_disc;
		return;
	}

		document.getElementById(txt).value='';		
		document.getElementById(div).innerHTML='';

}

</script>
<form action='' method='post' name='sch_entry_frm' onsubmit="return saveSchedule();">
<div id='Individual_div'>
</div>	
<div id='Group_div'>
</div>
<input type='hidden' name='tid' value='<?php echo $user->uid; ?>' />
	<table width="200" >
	<tr>
		<td>Day </td>
		<td>
		<select name="day" id="day">
			<option value="1">Monday</option>
			<option value="2">Tuesday</option>
			<option value="3">Wednesday</option>
			<option value="4">Thursday</option>
			<option value="5">Friday</option>
			<option value="6">Saturday</option>
			<option value="7">Sunday</option>
		</select>
		</td>
	</tr>
	<tr>
		<td>
			From 
		</td>
		<td>
			<select name='fhour' id='fhour' style='width:100px'>
				<?php
				for($i=0;$i<24;$i++)
					echo "<option value='{$i}'>{$i}</option>\n";
				?>
			</select>
	<!--
	: <select name='fmin' id='fmin'>
			<option value='00'>00</option>
			<option value='15'>15</option>
			<option value='30'>30</option>
			<option value='45'>45</option>
		</select>
	-->	
		</td>
	</tr>
	<tr>
		<td>
			To
		</td>
		<td>
			<select name='thour' id='thour' style='width:100px'>
				<?php
				for($i=0;$i<24;$i++)
					echo "<option value='{$i}'>{$i}</option>\n";
				?>
			</select>
			
	<!--	
	: <select name='tmin' id='tmin'>
			<option value='00'>00</option>
			<option value='15'>15</option>
			<option value='30'>30</option>
			<option value='45'>45</option>
		</select>		
	-->	
		</td>
	</tr>	
	<tr>
		<td>
			Individual
		</td>
		<td>
			<input type='radio' name='ltype' value='I' checked='checked' />
		</td>
	</tr>
	<tr>
		<td>
			Group 
		</td>
		<td>
			<input type='radio' name='ltype' value='G' />
		</td>
	</tr>
	<tr>
		<td>
			Entire Day
		</td>
		<td>
			<input type='checkbox' name='eday'  onclick='disableElements();'/>
		</td>
	</tr>
</table>
	<input type='button' name='Add' value='Add' onclick='addSchedule();' />	
	<br/>	<br/>	<br/>
	<style>
	#sub2{display:none;}
	#sub3{display:none;}	
	</style>
<b>Individual Lession Price</b><br/>
<?php
$res = db_query("select * from teacher_subjects_master ORDER BY subject ASC");
$sub_list='<option value="0">Select Subject</option>';
while($row=db_fetch_object($res))
{	
	$sub_list.="<option value='".$row->sub_id."'>".$row->subject."</option>";
}	
echo "<table border=0>";
echo "<tr><td width=300px>";
echo "Subject-1 <select name='subject1' id='subject1'>";
echo $sub_list;
echo "</select>";	


echo "<div id='sub2'  >";
echo "Subject-2 <select name='subject2' id='subject2'>";
echo $sub_list;
echo "</select>";
echo "<input type='button' name='addSubList2' id='addSubList2' value='-' onclick='hideList(2);' />";	
echo "</div>";

echo "<div id='sub3'>";
echo "Subject-3 <select name='subject3' id='subject3'>";
echo $sub_list;
echo "</select>";	
echo "<input type='button' name='addSubList2' id='addSubList2' value='-' onclick='hideList(3);' />";	
echo "</div>";

echo "</td>";
echo "<td valign='top'>";
echo "<input type='button' name='addSubList1' id='addSubList1' value='+' onclick='addSubjectList();' title='Add Subject' />";
echo "</td></tr></table>";
?>
	Price for 30 mins&nbsp;&nbsp;&nbsp;€   
	<select name='price_of_30min' id='price_of_30min' style='width:100px'>
		<option value="11">11</option>
		<option value="16">16</option>
		<option value="21">21</option>	
		<option value="26">26</option>	
	</select>	
	&nbsp;&nbsp;
	Price for 45 mins&nbsp;&nbsp;&nbsp;€  
	<select name='price_of_45min' id='price_of_45min'  style='width:100px'>
		<option value="16">16</option>
		<option value="21">21</option>
		<option value="26">26</option>	
		<option value="31">31</option>	
	</select>	
	<br/>
	<br/>	
	
	<b>Group Lessions Price</b><br/>
	Price for 45 mins&nbsp;&nbsp;&nbsp;€ <input type='text' name='group_lession_45min' />
	<br/> 

	<br/>
	<br/>	
	
	<b>Packages</b><br/>

	<table border=1>
	<tr>
		<td>
			Lessions	
		</td>
		<td>
			Minutes
		</td>
		<td>
			Discount %	
		</td>
		<td>
			Price After Discount	
		</td>
	</tr>
	
	<tr>
		<td rowspan="2">
			10 Lessions	
		</td>
		<td>
			30 Minutes
		</td>
		<td>
			<input type='text' name='10_less_30min_discount' maxlength="2"  onkeyup="disc(10,30, this.value);"/> (from 1 to 10)	
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;€
			<span id='10_less_30min_disc_div'></span>
			<input type='hidden' name='10_less_30min_disc_txt' id='10_less_30min_disc_txt'  />
		</td>
	</tr>
	<tr>
		<td>
			45 Minutes
		</td>
		<td>
			<input type='text' name='10_less_45min_discount' maxlength="2"  onkeyup="disc(10,45, this.value);"/> (from 1 to 10)	
		</td>
		<td>
			&nbsp;&nbsp;&nbsp;€		
			<span id='10_less_45min_disc_div'></span>
			<input type='hidden' name='10_less_45min_disc_txt' id='10_less_45min_disc_txt' />			
		</td>
	</tr>
	
	<tr>
		<td rowspan="2">
			30 Lessions	
		</td>
		<td>
			30 Minutes
		</td>
		<td>
			<input type='text' name='30_less_30min_discount' maxlength="2"  onkeyup="disc(30,30, this.value);"/> (from 1 to 10)	
		</td>
	
		<td>
			&nbsp;&nbsp;&nbsp;€
			<span id='30_less_30min_disc_div'></span>
			<input type='hidden' name='30_less_30min_disc_txt' id='30_less_30min_disc_txt'/>
		</td>
	</tr>
	<tr>
		<td>
			45 Minutes
		</td>
		<td>
			<input type='text' name='30_less_45min_discount' maxlength="2"  onkeyup="disc(30,45, this.value);"/> (from 1 to 10)	
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;€
			<span id='30_less_45min_disc_div'></span>
			<input type='hidden' name='30_less_45min_disc_txt' id='30_less_45min_disc_txt'/>			
		</td>
	</tr>
		
	<tr>
		<td rowspan="2">
			50 Lessions	
		</td>
		<td>
			30 Minutes
		</td>
		<td>
			<input type='text' name='50_less_30min_discount' maxlength="2"  onkeyup="disc(50,30, this.value);"/> (from 1 to 10)	
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;€
			<span id='50_less_30min_disc_div'></span>
			<input type='hidden' name='50_less_30min_disc_txt' id='50_less_30min_disc_txt'/>
		</td>
	</tr>
	<tr>
		<td>
			45 Minutes
		</td>
		<td>
			<input type='text' name='50_less_45min_discount' maxlength="2"  onkeyup="disc(50,45, this.value);"/> (from 1 to 10)	
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;€
			<span id='50_less_45min_disc_div'></span>
			<input type='hidden' name='50_less_45min_disc_txt' id='50_less_45min_disc_txt'/>			
		</td>
	</tr>		
	
	<tr>
		<td rowspan="2">
			100 Lessions	
		</td>
		<td>
			30 Minutes
		</td>
		<td>
			<input type='text' name='100_less_30min_discount' maxlength="2"  onkeyup="disc(100,30, this.value);"/> (from 1 to 10)	
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;€
			<span id='100_less_30min_disc_div'></span>
			<input type='hidden' name='100_less_30min_disc_txt' id='100_less_30min_disc_txt'/>
		</td>
	</tr>
	<tr>
		<td>
			45 Minutes
		</td>
		<td>
			<input type='text' name='100_less_45min_discount' maxlength="2"  onkeyup="disc(100,45, this.value);"/> (from 1 to 10)	
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;€
			<span id='100_less_45min_disc_div'></span>
			<input type='hidden' name='100_less_45min_disc_txt' id='100_less_45min_disc_txt' />			
		</td>
	</tr>			
	
	</table>

	<div style='text-align:right;margin-right:30px;'>
	<input type='submit' name='save' id="save" value='Save' class="form-submit art-button"  />
	</div>
	
</form>

