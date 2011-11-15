<?php

global $base_url;

//echo "<pre>";print_r($_SESSION);echo "</pre>";
?>
<table width="100%" border="0">
	<tr>
		<td width="200px" align="center"><img src='<?php echo $base_url."/".$result['picture']; ?>' /></td>
		<td>I look forward to your first lesson together.<br/> Please feel free to send me a message before the lesson.</td>
	</tr>
	<tr>
		<td align="center"><img src='<?php echo $base_url."/sites/default/files/calendar.png"; ?>' height="65" /></td>
		<td>
			<b>
			<?php echo $_SESSION['hour'].":".str_pad ($_SESSION['mins'], 2, 0, STR_PAD_LEFT); ?> - <?php echo $_SESSION['to_hrs'].":".str_pad ($_SESSION['to_mins'], 2, 0, STR_PAD_LEFT); ?></b> - 
			(<?php echo $_SESSION['duration_mins'] ?> Minutes )<br/>
			<?php 

				$date=strtotime($_SESSION['booked_on_date']); 
				echo date("l dS F Y",$date);
			
			?>
			<br/>
			<!-- You can reschedule our meeting at another date and time if you prefer.-->
		</td>
	</tr>
	<tr>
		<td align="center"><img src='<?php echo $base_url."/sites/default/files/enter.png"; ?>' height="65px" /></td>
		<td>Your teacher will be waiting for you in the Virtual ULearn class room. You can enter the classroom from your MyULearn at the time & date of the lesson.</td>
	</tr>
	<tr>
		<td align="center"><img src='<?php echo $base_url."/sites/default/files/Headphones.png"; ?>' height="85px" /></td>
		<td>
		Initially I will assess your basic level and we can plan together how to meet your needs and goals.<br/><br/>
		An email is being sent to you with further instructions.
		
		</td>
	</tr>
</table>

