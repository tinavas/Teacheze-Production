<div class="innerhead headblog">
    <span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/reg.png" /></span>
</div>
  
<div id="blog">
<div class="blog_container">
           
<p><a href="<?php echo url('sforms/view_reports_paid');?>" class="yellow_box">&lt;&lt; Back To Reports</a></p>
<br />
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

<div class="report_reg">
<a href="<?php echo url('sforms/add_comment');?>?type=paid&tid=<?php echo $row_det->id;?>" class="yellow_box">Add a comment</a>
<br /><br />
<?php
echo theme('view_vote_comments',$res_com);
?>
</div>

</div><!-- # eof blog -->
</div><!-- #eof blog_container -->