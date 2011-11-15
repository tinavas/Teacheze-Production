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
	
	checkEmptyAll('c_dept','Please select the department');
	checkEmptyAll('c_transaction','Please select transaction');
	checkEmptyAll('c_question','Please enter your question');
	
	if(getValue('email_id')!='')
	{
		checkEmail('email_id','Please enter a valid email address');
	}
	
	
	checkEmpty('security_code','Please enter the security code');
	checkCheckbox('t_and_c','You must agree to our terms and conditions');
			
	return showMessage();
}
</script>

      
		<!--Slide-->
	<div class="innerhead headblog2">
        <img class="askraghu" src="<?php echo  url('sites/all/themes/ipab_client');?>/images/raghu.jpg" title="RAGHU" alt="RAGHU" />
        <div class="askraghu_text">
		I am here to take you through a guided tour of the MAZE how corruption is plotted and executed and to provide you with some answers to your questions on how to avoid, resist, reduce and eliminate corruption. With 26 years of experience in the Government of India and of Karnataka State, as a former high ranking civil servant, I bring you the view from within the "system"        
        </div>
	</div><!--End Slide-->

	<div id="blog">
    <div class="blog_container divtab repot4 askr">

<div id="validation_errors" style="display:<?php echo (empty($msg))?'none':'block';?>;">
<?php
	echo $msg;
?>
</div>


<form action="" onsubmit="return validate_m();" method="post">

           <fieldset>			              
            
            <div class="ask">
            <p><label for="c_dept">Departments</label></p>
	
        	<select class="sleft" name="c_dept" id="c_dept" onchange="getTransactions();">
            	<?php echo $dept_options ;?>
            </select>
            
           <script type="text/javascript">
		   document.getElementById('c_dept').value = '<?php echo @$_POST['c_dept'];?>';
		   </script>
                       
            <input type="text" name="others_dept" id="others_dept_cont" style="display:none;"  value="<?php echo @$_POST['others_dept'];?>" />
			</div>

             <div class="ask">
			<p><label for="c_transaction">Transaction</label></p>
        	<div id="sub_transaction_con" class="inline_div" style="margin:0; padding:0;">
        	<select class="sleft" name="c_transaction" disabled="disabled" id="c_transaction">
            	<option value="">Select Transaction</option>
            </select>
            <?php
			if(!empty($_POST['c_transaction']))
			{
			 ?>
             <script type="text/javascript">
			 getTransactions('<?php echo $_POST['c_transaction'];?>');
			 </script>
             <?php
			}
			?>            
            </div>
            
            <input type="text" name="others_transaction" style="display:none;" id="others_transaction_cont" value="<?php echo @$_POST['others_transaction'];?>" />
            </div>    
            
           <div class="big">
            <p><label for="c_question">Question (2000 chars)</label></p> 
			<textarea name="c_question" id="c_question" onkeypress="return limitChars(this.id,2000,'info_char');"  rows="10" cols="4" ><?php echo @$_POST['c_question'];?></textarea>
            <p><span id="info_char"></span></p>
			</div>
            
            <div class="ask">
             <p><label for="email_id">Email (write down your email here if you wish to be informed when your question is answered)</label></p>
	      	                    
            <input type="text" name="email_id" id="email_id" value="<?php echo @$_POST['email_id'];?>" />
			 </div>
             
           <div class="big2">
			<p><label for="security_code">Please enter the security code</label></p>
            <p>
            	<img src="<?php echo url('sforms/get_spam_image').'?id='.rand('10000','999999');?>" alt="" />
            </p>
        	<input type="text" name="security_code" id="security_code" value="" />
			</div>
            
           <div class="sub">
			<input name="t_and_c" id="t_and_c" type="checkbox" value="1" class="no" />I agree to <a href="<?php echo url('node/78');?>" target="_blank"> the terms and conditions</a>
            <input type="hidden" name="fsubmit" value="fsubmit" />
            <input type="image" src="<?php echo  url('sites/all/themes/ipab_client').'/images/';?>submit.png" class="sub_bu" name="submit" id="submit" value="Submit"  />
			</div>
            
            </fieldset>
</form>

</div></div><!-- eeof report form -->