<?php
drupal_add_js('sites/all/modules/sforms/js/validation.js', 'core', 'header');
?>
<script type="text/javascript">

function changeColor(activeid,deactiveid)
{
	return;
    document.getElementById(activeid+'_l').style.color = '#651B1C';
    document.getElementById(deactiveid+'_l').style.color = '#ccc';
}

function getTransactions(selected)
{
    var c_dept = $('#c_dept').val();
    var url = '<?php echo url('sforms/get_transactions');?>?others=true&selected='+selected+'&did='+c_dept;            
    $('#sub_transaction_con').load(url);    
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
    
    checkEmptyAll('c_name','Please enter title');
	checkEmptyAll('c_city','Please select city');
	checkEmptyAll('c_dept','Please select the department');
	checkEmptyAll('c_transaction','Please select transaction');
    checkCheckbox('t_and_c','You must agree to our terms and conditions');    
    checkEmpty('security_code','Please enter the security code');
            
    return showMessage();
}

</script>

		<!--Slide-->
	<div class="innerhead">
	<span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/report-2.png" /></span>
    <a href="<?php echo url('sforms/view_reports_didnt_have_to_pay');?>"><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/view-reports.png" title="view reports" /></a>
	</div><!--End Slide-->

	<div id="inn_content">

    <div class="divtab_nav">
<ul>
<li><a href="<?php echo url('sforms/register/complaints');?>">I paid a bribe</a></li>
<li><a href="<?php echo url('sforms/register/i_didnt_pay_a_bribe');?>">I didn't pay a bribe</a></li>
<li class="act"><a href="<?php echo url('sforms/register/i_didnt_have_to_pay_a_bribe');?>">I didn't have to pay a bribe</a></li>
<li><a href="<?php echo url('faq');?>">I don't want to pay a bribe</a></li>
</ul>
    </div>
    <div class="clear"></div>
    <div class="divtab repot3">
    
<div class="report_form">

<div id="validation_errors" style="display:<?php echo (empty($msg))?'none':'block';?>;">
<?php
    echo $msg;
?>
</div>


<form action="" onsubmit="return validate_m();" method="post">
           
           <fieldset>  
                 
           <div>  
           <label for="c_city">City</label>
           <?php echo $city_dropdown;?>
           <script type="text/javascript">
           document.getElementById('c_city').value = '<?php echo @$_POST['c_city'];?>';
           </script>           
              </div>


               <div>  
            <label for="c_dept">Departments</label>
    
            <select class="sleft" name="c_dept" id="c_dept" onchange="getTransactions();">
                <?php echo $dept_options ;?>
            </select>
            
           <script type="text/javascript">
           document.getElementById('c_dept').value = '<?php echo @$_POST['c_dept'];?>';
           </script>         
            <input type="text" name="others_dept" id="others_dept_cont" style="display:none;"  value="<?php echo @$_POST['others_dept'];?>" />
            </div>

            <div>  
            <label for="c_transaction">Transaction</label>
            <div id="sub_transaction_con" class="inline_div" style="margin:0; padding:0;">
            <select class="sleft" name="c_transaction" disabled="disabled" id="c_transaction">
                <option value="">Select Transaction</option>
            </select>            
            </div>
            
            <input type="text" name="others_transaction" style="display:none;" id="others_transaction_cont" value="<?php echo @$_POST['others_transaction'];?>" />
            
            <?php
			if(@$_POST['c_transaction']!='')
			{
			 ?>
             <script type="text/javascript">
			 getTransactions('<?php echo $_POST['c_transaction'];?>');
			 </script>
             <?php
			}
			?>            
            </div>
           
           <div>
           <label for="c_bribe_type">Bribe Type</label>
                <select class="sleft" name="c_bribe_type" id="c_bribe_type">
                    <option value="personal">Personal</option>
                    <option value="corporate">Corporate</option>
                </select>
                <script type="text/javascript">
                document.getElementById('c_bribe_type').value = '<?php echo @$_POST['c_bribe_type'];?>';
                </script>
            </div>
                
           <div class="bigcen">
           <!-- <p>
               <input type="radio" name="resisted_type" checked="checked" id="resisted_by" onclick="changeColor('resisted_by','govt_official');" value="nongovt" /> <label id="resisted_by_l" for="resisted_by">Resisted a bribe</label>
            
            <select class="sleft" name="c_bribe_resisted_by" style="float:none;" id="c_bribe_resisted_by">
                <option value="">Select</option>
                <option value="me">Me</option>
                <option value="friend">Friend</option>
                <option value="relative">Relative</option>
            </select>
            </p>  --> 
            
          
            <input type="radio" name="resisted_type" checked="checked" onclick="changeColor('govt_official','resisted_by');" id="govt_official" value="govt" /> <label id="govt_official_l" for="govt_official">Came accross</label> &nbsp;<strong>An honest goverment official.</strong>
                 
            <script type="text/javascript">
            document.getElementById('c_bribe_resisted_by').value = '<?php echo @$_POST['c_amt_paid'];?>';
            changeColor('resisted_by','govt_official');
            </script>                
            </div>            
            
           <div class="big">
            <label for="c_name">Title your story</label>
            <textarea name="c_name" id="c_name"  rows="2" style="height:20px;" cols="4" ><?php echo @$_POST['c_name'];?></textarea>
            </div>
            
           <div class="big">
           <label for="c_addi_info">Tell us your story</label>
            <textarea class="hi" name="c_addi_info" id="c_addi_info"  rows="10" cols="4"><?php echo @$_POST['c_addi_info'];?></textarea>
            </div>

           <div class="big2">
           <label for="security_code">Please enter the security code</label>
            <p>
                <img src="<?php echo url('sforms/get_spam_image').'?id='.rand('10000','999999');?>" alt="" />
            </p>
            <input type="text" name="security_code" id="security_code" value="" />
            </div>
            
           <div class="sub">
            <input name="t_and_c" id="t_and_c" type="checkbox" value="1" class="no" />I agree to <a href="<?php echo url('node/78');?>" target="_blank"> the terms and conditions</a>
 
 			<input type="hidden" name="fsubmit" value="fsubmit" />
			<input type="image" src="<?php echo  url('sites/all/themes/ipab_client').'/images/';?>submit.png" class="sub_bu" name="submit" id="submit" value="Submit" />
            </div>
                                
            </fieldset>
</form>

</div></div></div>