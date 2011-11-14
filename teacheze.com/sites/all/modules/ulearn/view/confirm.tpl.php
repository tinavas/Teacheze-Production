<?php

global $base_url;


$vals = db_fetch_object (db_query ("SELECT * FROM {settings}"));

if ($vals->mode == 1) {

	$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

	$vals->paypal = 'nandas_1253770731_biz@yahoo.com';

} else {

	$url = 'https://www.paypal.com/cgi-bin/webscr';

}


/*	$_SESSION['tid'] = $_POST['tid'];
	$_SESSION['subject_list'] = $_POST['subject_list'];
	$_SESSION['booked_on_date'] = $booked_on_date;
	$_SESSION['hour'] = $_POST['hour'];
	$_SESSION['mins'] = $_POST['mins'];
	$_SESSION['to_hrs'] = $to_hrs;
	$_SESSION['to_mins'] = $to_mins;
	$_SESSION['duration_mins'] = $duration_mins;
	$_SESSION['package_id'] = $package_id;*/


$r=db_query("select * from teacher_lession_prices WHERE tid='".$_SESSION['tid']."' LIMIT 1");
$lesson_pr=db_fetch_object($r);

if($_SESSION['lesson_type']=='I')
{
	$lesson_type = "Individual";
	$duration=$_SESSION['duration_mins'];
	if($_SESSION['duration_mins']==30)
		$price = $lesson_pr->price_of_30min;
	else
		$price = $lesson_pr->price_of_45min;		
		
	$price=sprintf("%.2f", abs($price));
	$discount=0.00;
	$amount=$price;
		
}
if($_SESSION['lesson_type']=='G')
{
	$lesson_type = "Group";
	$duration=$_SESSION['duration_mins'];
	//if(isset($_POST['group_45mins']))
	$price = $lesson_pr->group_lesson_45min ;

	$price=sprintf("%.2f", abs($price));
	$discount=0.00;
	$amount=$price;
	
}
if($_SESSION['lesson_type']=='P')
{
	$lesson_type = "Lesson Package";

	$pkres=db_query("SELECT * FROM `teacher_discount_packages` WHERE tid='".$_SESSION['tid']."' LIMIT 1"); 
	$pk=db_fetch_object($pkres);
	
	$duration=$_SESSION['duration_mins'];		
	
	$field = $_SESSION['package_id']."_less_".$duration."min_discount";
	
	$discount = $pk->$field; 			
	if($_SESSION['duration_mins']==30)
	{
		$pk_price = $lesson_pr->price_of_30min * $_SESSION['package_id'];
	}
	else
	{
		$pk_price = $lesson_pr->price_of_30min * $_SESSION['package_id'];
	}

	$disc_amt = $pk_price * $discount/100;
	$price = $pk_price - $disc_amt;
	$price =  sprintf("%.2f", abs($pk_price));
	$discount = sprintf("%.2f", abs(($disc_amt)));
	$amount = sprintf("%.2f", abs($price - $disc_amt));

	
}




if ( $result->p_curr == 'EUR' ) {

	$curr_sym = '&euro;';

} elseif ( $result->p_curr == 'USD' ) {

	$curr_sym = '&#036;';

} elseif ( $result->p_curr == 'GBP' ) {

	$curr_sym = '&pound;';

}

?>

<a href="<?php echo $base_url.'/student_booking?uid='.$_SESSION['tid'];?>" style="float:right;">Back</a>
<h4 style="clear:right;">Please verify the below data before procedding to paypal.</h4>
<table style="width:600px;">
<tr>
<td>Selected Package </td>
<td><b> : </b></td>
<td><b>
<?php 
			echo $lesson_type;
			?>
</b></td>
</tr>
<tr>
<td>Time per each session </td>
<td width="50px"><b> : </b></td>
<td><b><?php echo $duration ?> Minutes</b> (<?php echo $_SESSION['hour'].":".str_pad ($_SESSION['mins'], 2, 0, STR_PAD_LEFT); ?> - <?php echo $_SESSION['to_hrs'].":".str_pad ($_SESSION['to_mins'], 2, 0, STR_PAD_LEFT); ?>)</td>
</tr>
<tr>
<td>Price </td>
<td><b> : </b></td>
<td><b><?php echo $curr_sym . ' ' . $price; ?></b></td>
</tr>
<tr>
<td>Discount </td>
<td><b> : </b></td>
<td><b><?php echo $curr_sym . ' ' . $discount; ?></b></td>
</tr>
<tr>
<td>Final Amount </td>
<td><b> : </b></td>
<td><b><?php echo $curr_sym . ' ' . $amount; ?></b></td>
</tr>
<tr>
<td colspan="3" align="center"><br>
<br>
<form name="payment" id="payment" method="post" action="<?php echo $url;?>">
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="redirect_cmd" value="_xclick" />
<input type="hidden" name="business" value="<?php echo $vals->paypal; ?>" />
<input type='hidden' name="item_name" value="<?php echo $_SESSION['lesson_type'];?>" />
<input type="hidden" name="amount" value="<?php echo $amount;?>" />
<input type="hidden" name="currency_code" value="<?php echo $result->p_curr; ?>" />
<input type="hidden" name="return" value="<?php echo $base_url.'/success_payment'; ?>" />
<input type="hidden" name="cancel_return" value="<?php echo $base_url.'/cancel_payment'; ;?>" />
<input type="hidden" name="rm" value="2" />
<input type="button" name="confirm_order" value="Confirm Order" />
</form></td>
</tr>
</table>
<script type="text/javascript">

$(document).ready(function() {

	$('form#payment').click (function() {

		$.ajax({

   			type: "POST",

			url: "<?php echo $base_url;?>/proceed_payment",
   			//data: "p_id=<?php echo $result->pid;?>&pt_id=<?php echo $result->ptid;?>&p_amount=<?php echo $amount;?>",
			data: "p_amount=<?php echo $amount;?>",

   			success: function(msg){

				alert (msg);

				$('#payment').submit();

   			}

 		});

		return false;

	});

});

</script>