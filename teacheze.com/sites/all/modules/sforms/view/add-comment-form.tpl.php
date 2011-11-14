<?php
drupal_add_js('sites/all/modules/sforms/js/validation.js', 'core', 'header');
?>

<?php
$row_det = get_post_details($_REQUEST['type'],$_REQUEST['tid']);

$type = $_REQUEST['type'];

if($type=='paid')
{
    $redurl = 'sforms/view_reports_paid';
    $head_img = 'reg.png';
}
else if($type=='notpaid')
{
    $redurl = 'sforms/view_reports_didnt_pay';
    $head_img = 'reg.png';
}
else if($type=='dinthvtopay')
{
    $redurl = 'sforms/view_reports_didnt_have_to_pay';
    $head_img = 'reg.png';
}
else if($type=='raghuans')
{
    $redurl = 'sforms/view_raghu_answers';
    $head_img = 'reg.png';
}

?>
  

<script type="text/javascript">
function validate_m()
{
	msg = '';
	
	checkEmptyAll('subject','Please enter subject');
	checkEmptyAll('comment','Please enter your comment.');
			
	return showMessage();
}
</script>

<div id="innerhead" class="innerhead headblog">
 <span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/<?php echo $head_img;?>" /></span>
</div>

<div class="blog_container divtab">

<p><a href="<?php echo url($redurl);?>" class="yellow_box">&lt;&lt; Back To Reports</a></p>
<br />

<?php
if($type=='paid')
{
	?>
    <h3><?php echo $row_det->c_name;?></h3>
    <div class="report_reg">
            Reported : <?php echo getDateFormat($row_det->created,'F j, Y - H:i');?> 
            | City : <?php echo $row_det->c_city;?> 
            | Paid On : <?php echo getDateFormat($row_det->c_date_paid.' 00:00:00','F j, Y ');?> 
            | <?php echo ($row_det->c_dept!=0)?$row_det->dept_name:getOthers($row_det->others_dept);?>
    </div>
    <div class="report_reg">
    <table class="details_table" width="100%" summary="this table has the details for a certain report.">
        <tr>
            <th width="20%"><span class="desc_lebel">Department:</span></th>
            <td><?php echo ($row_det->c_dept!=0)?$row_det->dept_name:getOthers($row_det->others_dept);?></td>
        </tr>
        <tr>
            <th><span class="desc_lebel">Transaction:</span></th>
            <td><?php echo ($row_det->c_transaction!=0)?$row_det->trans_name:getOthers($row_det->others_transaction);?></td>
        </tr>    
        <tr>
            <th><span class="desc_lebel">Bribe Type:</span></th>
            <td><?php echo $row_det->c_bribe_type;?></td>
        </tr>
        <tr>
            <th><span class="desc_lebel">Details:</span></th>
            <td><?php echo nl2br(strip_tags($row_det->c_addi_info));?></td>
        </tr>
    </table>
    </div>
    <?php	
}
else if($type=='notpaid')
{
	?>
	<h3><?php echo $row_det->c_name;?></h3>
    
    <div class="report_reg">
Reported : <?php echo getDateFormat($row_det->created,'F j, Y - H:i');?> | City : <?php echo $row_det->c_city;?> | <?php echo ($row_det->c_dept!=0)?$row_det->dept_name:getOthers($row_det->others_dept);?>

		<table class="details_table">
			<tr>
		    	<th width="20%"><span class="desc_lebel">Department:</span></th>
		        <td><?php echo ($row_det->c_dept!=0)?$row_det->dept_name:getOthers($row_det->others_dept);?></td>
		    </tr>
			<tr>
		    	<th><span class="desc_lebel">Transaction:</span></th>
		        <td><?php echo ($row_det->c_transaction!=0)?$row_det->trans_name:getOthers($row_det->others_transaction);?></td>
		    </tr>    
			<tr>
		    	<th><span class="desc_lebel">Reason:</span></th>
		        <td>
                <?php 
                    if($row_det->c_bribe_resisted_by=='govt')
                    {
                        echo 'Came accross an honest govt official';
                    }
                    else
                    {
                        echo 'Resisted by '.$row_det->c_bribe_resisted_by;
                    }
                ?>
                </td>
		    </tr>
			<tr>
		    	<th><span class="desc_lebel">Details:</span></th>
		        <td><?php echo nl2br(strip_tags($row_det->c_addi_info));?>
		</td>
		    </tr>
		</table>
    </div>     
    <?php
}
else if($type=='dinthvtopay')
{
	?>
	<h3><?php echo $row_det->c_name;?></h3>
    
    <div class="report_reg">
City : <?php echo $row_det->c_city;?> | <?php echo ($row_det->c_dept!=0)?$row_det->dept_name:getOthers($row_det->others_dept);?>

    
    <table class="details_table">
			<tr>
		    	<th width="20%"><span class="desc_lebel">Department:</span></th>
		        <td><?php echo ($row_det->c_dept!=0)?$row_det->dept_name:getOthers($row_det->others_dept);?></td>
		    </tr>
			<tr>
		    	<th><span class="desc_lebel">Transaction:</span></th>
		        <td><?php echo ($row_det->c_transaction!=0)?$row_det->trans_name:getOthers($row_det->others_transaction);?></td>
		    </tr>    
			<tr>
		    	<th><span class="desc_lebel">Reason:</span></th>
		        <td>
                <?php 
                    if($row_det->c_bribe_resisted_by=='govt')
                    {
                        echo 'Came accross an honest govt official';
                    }
                    else
                    {
                        echo 'Resisted by '.$row_det->c_bribe_resisted_by;
                    }
                ?>
                </td>
		    </tr>
			<tr>
		    	<th><span class="desc_lebel">Details:</span></th>
		        <td><?php echo nl2br(strip_tags($row_det->c_addi_info));?>
		</td>
		    </tr>
		</table>
        </div>    
    <?php
}
else if($type=='raghuans')
{
	?>
    <div class="report_reg">
		<p class="question"><h2> <strong>Q.</strong> <?php echo $row_det->c_question;?>?</h2></a></p>
		<div id="ans_1" style="display:block; margin:15px 0px 0px 0px;">
			<span class="answer">A. </span><br /> <?php echo html_entity_decode($row_det->reply_ans);?>
    	</div>
    </div>     
    <?php
}
?>

<div style="clear:both;"></div>
<div id="validation_errors" style="display:<?php echo (empty($msg))?'none':'block';?>;">
<?php
	echo $msg;
?>
</div>

<h1 class="c_head">Add comment</h1>

<form method="post" action="" onsubmit="return validate_m();">
<table>
	<tr>
    	<td colspan="2">
        <label for="subject">Subject</label><br />
        <input type="text" name="subject" size="84" value="<?php echo @$_REQUEST['subject'];?>" id="subject" /></td>
    </tr>
	<tr>
    	<td colspan="2">
        <br />
        <label for="comment">Comment</label><br />
        <textarea rows="5" cols="40" name="comment" id="comment"><?php echo @$_REQUEST['comment'];?></textarea>
        </td>
    </tr>    
    
    <tr>
    <td colspan="2">
    <label for="security_code">Please enter the security code</label>
    <p><img src="<?php echo url('sforms/get_spam_image').'?id='.rand('10000','999999');?>" alt="" /></p>
    <input type="text" name="security_code" id="security_code" value="" />    
    </td>
    </tr>
    
	<tr>    	
        <td colspan="2">
        <input type="hidden" name="fsubmit" value="fsubmit" />
        <input type="hidden" name="type" value="<?php echo $_REQUEST['type'];?>" />
        <input type="hidden" name="type_id" value="<?php echo $_REQUEST['tid'];?>" />
        <input type="image" src="<?php echo  url('sites/all/themes/ipab_client').'/images/';?>post_btn.png" class="sub_bu" name="submit" id="submit" value="Post"  />
        </td>
    </tr>    
</table>
</form>

</div>