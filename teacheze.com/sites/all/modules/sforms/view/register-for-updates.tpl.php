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


<!-- Code begin for lightBox -->

<style type="text/css">
<!--
#box
{
position: absolute;
top:25%;
left:25%;
width:50%;
text-align: center;
display: none;
}

#form{
background: #000;
width: 450px;
height: 425px;
margin: 0 auto;
}
-->
</style>
<a href="#" id='in'>asd</a>

<div id="box"> <!-- This container centers the box in your browser windo -->

<div id="form"></div> <!-- This is the actual box.-->

</div><!-- end #box-->

<script type="text/javascript">


		$("a#in").click(function(){
			$("div#box").fadeIn("slow");
		});

		$("a#close").click(function(event){
			$("div#box").fadeOut("fast");
		});


</script>

<!-- Code end for lightBox -->


<div class="report">
<div class="report_top">
<h1>Sign the Pledge</h1>
</div>
</div>

<div class="clear"></div>

<div id="validation_errors" style="display:<?php echo (empty($msg))?'none':'block';?>;">
<?php
	echo $msg;
?>
</div>

&nbsp;

<div class="report_form">
<p><b>
<br />Paying a bribe is not an alternative anymore. I pledge to take my first step towards ‘corrup – shun’. Starting today, I refuse to be a victim.
I will be the change I want to see.

Use this text as the static text for pledge </b>
</p><br /><br />

<b><?php echo $number_of_people_signed;?> people have signed Already!!!</b>
<form action="" onsubmit="return validate_m();" method="post">

        	<div>  
			<p><label for="EMail">EMail</label></p>
        	<div id="sub_transaction_con" class="inline_div">
            <input type="text" name="email" id="email"  value="<?php echo @$_POST['email'];?>" />         
            </div>
            </div>  
            

            <div>  
			<p><label for="security_code">Please enter the security code</label></p>
            <p>
            	<img src="<?php echo url('sforms/get_spam_image').'?id='.rand('10000','999999');?>" alt="" />
            </p>
        	<input type="text" name="security_code" id="security_code" value="" />
			</div>
            <div class="report_form_button">  
            <input type="hidden" name="fsubmit" value="fsubmit" />
			<input type="submit" name="submit" id="submit" class="submit_btn" value="Submit" />
			</div>      
                 
</form>

</div><!-- eeof report form -->