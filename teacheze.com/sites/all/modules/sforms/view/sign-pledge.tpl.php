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

	checkEmpty('client_name','Please enter your name');
	checkEmail('email','Please enter your email');

	
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

<script type="text/javascript">


		$("a#in").click(function(){
			$("div#box").fadeIn("slow");
		});

		$("a#close").click(function(event){
			$("div#box").fadeOut("fast");
		});


</script>

<!-- Code end for lightBox -->


		<!--Slide-->
	<div class="innerhead headblog">
	<span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/pledge.png"  class="moremr"  /></span>
	</div><!--End Slide-->

	<div id="blog">
    <div class="blog_container divtab">

   <div class="pledge">
    <p>

    Paying a bribe is <span class="pledge_1">NOT</span> an alternative anymore. 
I pledge to take my first step towards <span class="pledge_2">‘corrup–shun’.</span> 
Starting today, I <span class="pledge_3">refuse</span> to be a victim. 
    </p>
    <h2>I will be the change I want to see.</h2>

<div id="validation_errors" style="display:<?php echo (empty($msg))?'none':'block';?>;">
<?php
	echo $msg;
?>
</div>

<form action="" onsubmit="return validate_m();" method="post">
           <fieldset>  
           <div class="pledgeform">
			<label for="name">*Name</label>
            <input type="text" name="client_name" id="client_name"  value="<?php echo @$_POST['client_name'];?>" />
			</div>

           <div class="pledgeform">
			<label for="EMail">*EMail</label>
            <input type="text" name="email" id="email"  value="<?php echo @$_POST['email'];?>" />         
            </div>  
            
            <div class="report_form_button">  
            <input type="hidden" name="fsubmit" value="fsubmit" />
			<input type="image" src="<?php echo url('sites/all/themes/ipab_client/');?>images/submit-pledge.png" class="sub_bu"  name="submit" id="submit" value="Submit" />
			</div>      
           </fieldset>
            
</form>

</div>
    <div class="pledge_right">
    <h1><?php echo $number_of_people_signed;?></h1>
<span>total pledge submissions</span>
    </div>
    
    
    </div></div><!-- eeof report form -->