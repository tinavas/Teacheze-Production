<script type="text/javascript">
function getTransactions(selected)
{
	var c_dept = $('#c_dept').val();
	var url = '<?php echo url('sforms/get_transactions');?>?others=false&all=true&selected='+selected+'&did='+c_dept;			
	$('#sub_transaction_con').load(url);
}
</script>
<script type="text/javascript">
	function show_less(num)
	{
		$('#more_d_'+num).css('display','none');
		$('#less_d_'+num).fadeIn();
		
	}
	
	function show_more(num)
	{
		$('#less_d_'+num).css('display','none');
		$('#more_d_'+num).fadeIn();
		$.ajax({
			  type: "GET",
			  url: 'increment_count',
			  data: "tableName=dint_have&SNO="+num,
			  success: function(data) {
				$("#count"+num+'_1').html(parseInt($("#count"+num+'_1').html())+1);
				$("#count"+num+'_2').html(parseInt($("#count"+num+'_2').html())+1);
			  }
			});
	}	
</script>

<!--Slide-->
	<div class="innerhead">
	<span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/reg.png" /></span>
    <a href="<?php echo url('sforms/register/complaints');?>"><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/addreport.png" title="add reports" /></a>
	</div>
<!--End Slide-->

	<div id="inn_content">
    
    <div class="divtab_nav">
    <ul>
		<li><a href="<?php echo url('sforms/view_reports_paid');?>">I paid a bribe</a></li>
		<li><a href="<?php echo url('sforms/view_reports_didnt_pay');?>">I didn't pay a bribe</a></li>
		<li class="act"><a href="<?php echo url('sforms/view_reports_didnt_have_to_pay');?>">I didn't have to pay a bribe</a></li>
		<li class="button4"><a href="<?php echo url('faq');?>">I don't want to pay a bribe</a></li>
    </ul>
    </div>
    <div class="clear"></div>
    <div class="divtab report_form_head repot3">
    
<?php
if(mysql_num_rows($result)>0)
{
	?>
<form action="" method="post" name="myform">
<fieldset>

<div class="big report_lb_cl">
           <label>FILTER RESULTS</label>
</div>
           
           <div>
           <label>City</label>
		   <?php echo $city_dropdown ;?>   
           </div>
			
            
			<div>
            <label>Department</label>
            <select class="form-select" name="c_dept" id="c_dept" onchange="getTransactions(0);">
            	<option value="all">All</option>
            	<?php echo $dept_options ;?>
            </select>   
            </div>
            
            <div>
            <label>Transaction</label>
            <div id="sub_transaction_con" class="inline_div" style="margin:0; padding:0;">
        	<select class="form-select" name="c_transaction" disabled="disabled" id="c_transaction">
            	<option value="">Please Select Department</option>
            </select>
            </div>
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
            
            <div class="go">
            <input type="hidden" name="fsubmit" value="fsubmit" />
			<input type="image" src="<?php echo url('sites/all/themes/ipab_client/');?>images/go-2.png" class="sub_bu" name="go" value="Go" />            
            </div>
</fieldset>
</form>

<h1 class="c_head">Total reports: <?php echo $num_recs;?> and counting...</h1>
<?php
while ($row = db_fetch_object($result)) 
{
	$num_comment = get_num_comment('dinthvtopay',$row->id);
	?>
    <div class="report_reg">

	<h2><?php echo $row->c_name;?></h2>
    
    <div class="report_reg_det">
Reported : <?php echo getDateFormat($row->created,'F j, Y - H:i');?> | City : <?php echo $row->c_city;?> | <?php echo ($row->c_dept!=0)?$row->dept_name:getOthers($row->others_dept);?>
	</div>
    
	<div class="clear"></div>

	<div id="less_d_<?php echo $row->id;?>">
		<div id="more_link<?php echo $row->id;?>">
		<div class="report_reg_more">
        <a href="#" class="rad" onclick="show_more('<?php echo $row->id;?>'); return false;">Read more...</a>
        <a href="<?php echo url('sforms/add_comment');?>?type=dinthvtopay&tid=<?php echo $row->id;?>">Add Comment</a> 
        <a href="<?php echo url('sforms/view_comments_didnt_have_to_pay');?>?id=<?php echo $row->id;?>"><?php echo $num_comment;?> Comments</a> 
        <span id="count<?php echo $row->id;?>_1"><?php echo $row->count;?></span> views
        </div>
		</div>
	</div><!-- eof #less_d_num -->

	<div id="more_d_<?php echo $row->id;?>" style="display:none;">

	<div id="less_link<?php echo $row->id;?>">
	<div class="report_reg_more">
    <a href="#" class="rad" onclick="show_less('<?php echo $row->id;?>'); return false;">Read less...</a>
        <a href="<?php echo url('sforms/add_comment');?>?type=dinthvtopay&tid=<?php echo $row->id;?>">Add Comment</a> 
        <a href="<?php echo url('sforms/view_comments_didnt_have_to_pay');?>?id=<?php echo $row->id;?>"><?php echo $num_comment;?> Comments</a>        
    	<span id="count<?php echo $row->id;?>_2"><?php echo $row->count;?></span> views
    </div>
	</div>
    
    		<table class="details_table">
			<tr>
		    	<th width="20%"><span class="desc_lebel">Department:</span></th>
		        <td><?php echo ($row->c_dept!=0)?$row->dept_name:getOthers($row->others_dept);?></td>
		    </tr>
			<tr>
		    	<th><span class="desc_lebel">Transaction:</span></th>
		        <td><?php echo ($row->c_transaction!=0)?$row->trans_name:getOthers($row->others_transaction);?></td>
		    </tr>    
			<tr>
		    	<th><span class="desc_lebel">Reason:</span></th>
		        <td>
                <?php 
                    if($row->c_bribe_resisted_by=='govt')
                    {
                        echo 'Came accross an honest govt official';
                    }
                    else
                    {
                        echo 'Resisted by '.$row->c_bribe_resisted_by;
                    }
                ?>
                </td>
		    </tr>
			<tr>
		    	<th><span class="desc_lebel">Details:</span></th>
		        <td><?php echo nl2br(strip_tags($row->c_addi_info));?>
		</td>
		    </tr>
		</table>
        
        <div class="clear"></div>
        <div class="share_tool_d">
        <?php
        
           $share_url = urlencode($GLOBALS['base_url'].'/sforms/view_report_didnt_have_to_pay?id='.$row->id);
        ?>
                       
        <a href="http://www.facebook.com/share.php?u=<?php echo $share_url;?>" target="_blank" class="facebook_share_view"></a>
                &nbsp;&nbsp;
                <a href="http://twitter.com/share?url=<?php echo $share_url;?>" target="_blank" class="tweet_share_view"></a> 
        </div>
        
</div><!-- eof #more_d_num -->

<div class="clear"></div>
</div>
<?php
}//eof while

echo theme('pager', NULL, variable_get('default_nodes_main', 10));
?>
<?php
}//eof if for cecking num rows
else
{
	?>
    <div class="display_info">
    No reports found.
    </div>    
    <?php
}
?>
</div>