<?php
drupal_add_js('sites/all/modules/sforms/js/validation.js', 'core', 'header');
?>
<script type="text/javascript">
function getTransactions()
{
	var c_dept = $('#c_dept').val();
	
	$('#sub_transaction_con').load('<?php echo url('sforms/get_transactions');?>?did='+c_dept);
	
	checkDeptOthers();
}

function checkDeptOthers()
{
	if($('#c_dept').val()==0)
	{
		$('#others_dept_cont').fadeIn();
		$('#others_transaction_cont').fadeIn();
	}
	else
	{
		$('#others_dept_cont').fadeOut();
		$('#others_transaction_cont').fadeOut();
	}
}

function checkOthers()
{
	if($('#c_transaction').val()==0)
	{
		$('#others_transaction_cont').fadeIn();
	}
	else
	{
		$('#others_transaction_cont').fadeOut();
	}
}
</script>

<script type="text/javascript">
function validate_m()
{
	msg = '';

	checkEmail('email','Please enter your email');
	checkEmpty('security_code','Please enter the security code');
			
	return showMessage();
}
</script>


	<div class="innerhead headblog">
	<span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/vote.png" class="moremr" /></span>
    <h3>voted</h3><h2><?php echo $poll_details['count'];?> </h2>
	</div><!--End Slide-->


	<!--News Content--> 
	<div id="blog">
    <div class="blog_container divtab vv">
    
    
<div id="validation_errors" style="display:<?php echo (empty($msg))?'none':'block';?>;">
<?php
	echo $msg;
?>
</div>

<p><?php echo $poll_details['poll_desc'];?></p>
    <div class="middiv">
    <?php echo $poll_details['poll_title'];?>
    </div>
<form action="" onsubmit="return validate_m();" method="post">
<?php 
while($row=mysql_fetch_assoc($poll_questions))
{
	$SNo++;
			?>
			<div class="vote">
            <h2><?php echo $row['question'];?></h2>
			<input type="radio" checked="checked" id="a_<?php echo $row['id'];?>" value="A" name="option<?php echo $row['id'];?>" /><label for="a_<?php echo $row['id'];?>"><?php echo $row['option1'];?></label>
			<input type="radio" value="B" id="b_<?php echo $row['id'];?>" name="option<?php echo $row['id'];?>" /><label for="b_<?php echo $row['id'];?>"><?php echo $row['option2'];?></label>
            <input type="radio" value="C" id="c_<?php echo $row['id'];?>" name="option<?php echo $row['id'];?>" /><label for="c_<?php echo $row['id'];?>"><?php echo $row['option3'];?></label>
			<input type="hidden" value="<?php echo $row['id'];?>" name="SNo<?php echo $SNo;?>" />	
			</div>
			<?php 
}
?>			
            <div class="report_form_button">
            <input type="hidden" name="number_of_questions" value="<?php echo $SNo;?>" />
            <input type="hidden" name="fsubmit" value="fsubmit" />
			<input type="image" src="<?php echo  url('sites/all/themes/ipab_client');?>/images/submit.png" value="Submit" style="margin-left:300px;" class="submit_btn" name="submit" id="submit"  />
			</div>
                 
</form>

<div style="clear:both"></div>
<a href="<?php echo url('results-poll-1');?>" class="read_more_vote">View our previous poll results</a>
</div><!-- eeof report form -->
</div>