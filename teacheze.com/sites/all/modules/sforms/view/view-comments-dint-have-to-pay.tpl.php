<div class="innerhead headblog">
    <span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/reg.png" /></span>
</div>
  
<div id="blog">
<div class="blog_container">

<p><a href="<?php echo url('sforms/view_reports_didnt_have_to_pay');?>" class="yellow_box">&lt;&lt; Back To Reports</a></p>
<br />

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
        

<div class="report_reg">
<a href="<?php echo url('sforms/add_comment');?>?type=dinthvtopay&tid=<?php echo $row_det->id;?>" class="yellow_box">Add a comment</a>
<br /><br />
<?php
echo theme('view_vote_comments',$res_com);
?>   
</div>     

</div><!-- # eof blog -->
</div><!-- #eof blog_container -->