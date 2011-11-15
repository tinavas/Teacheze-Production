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

			  data: "tableName=paid_bribe&SNO="+num,

			  success: function(data) {

				$("#count"+num+'_1').html(parseInt($("#count"+num+'_1').html())+1);

				$("#count"+num+'_2').html(parseInt($("#count"+num+'_2').html())+1);

			  }

			});

	}	

</script>

<!--Slide-->

<div class="innerhead"> <span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/reg.png" /></span> <a href="<?php echo url('sforms/register/complaints');?>"><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/addreport.png" title="add reports" /></a> </div>

<!--End Slide-->

<div id="inn_content">
<div class="divtab_nav">
  <ul>
    <li class="act"><a href="<?php echo url('sforms/view_reports_paid');?>">I paid a bribe</a></li>
    <li><a href="<?php echo url('sforms/view_reports_didnt_pay');?>">I didn't pay a bribe</a></li>
    <li><a href="<?php echo url('sforms/view_reports_didnt_have_to_pay');?>">I didn't have to pay a bribe</a></li>
    <li><a href="<?php echo url('faq');?>">I don't want to pay a bribe</a></li>
  </ul>
</div>
<div class="clear"></div>
<div class="divtab report_form_head">
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
        <?php echo $city_dropdown ;?> </div>
      <div>
        <label>Department</label>
        <select class="form-select" name="c_dept" id="c_dept" onchange="getTransactions(0);">
          <option value="all">All</option>
          <?php echo $dept_options ;?>
        </select>
      </div>
      <div  class="edit_margin">
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
      <div class="go_report">
        <input type="hidden" name="fsubmit" value="fsubmit" />
        <input type="image" src="<?php echo url('sites/all/themes/ipab_client/');?>images/go-2.png" class="sub_bu" name="go" value="Go" />
      </div>
    </fieldset>
  </form>
  <h1 class="c_head_edit">Total reports: <?php echo $num_recs;?> and counting...</h1>
  <?php

while ($row = db_fetch_object($result)) 

{

	$num_comment = get_num_comment('paid',$row->id);

    

    ?>
  <div class="report_reg">
    <h2><?php echo $row->c_name;?></h2>
    <div class="report_reg_det"> Reported : <?php echo getDateFormat($row->created,'F j, Y - H:i');?> | City : <?php echo $row->c_city;?> | Paid On : <?php echo getDateFormat($row->c_date_paid.' 00:00:00','F j, Y ');?> | <?php echo ($row->c_dept!=0)?$row->dept_name:getOthers($row->others_dept);?> </div>
    <div class="report_reg_paid">PAID<span><?php echo $row->c_amt_paid;?></span></div>
    <div class="clear"></div>
    <div id="less_d_<?php echo $row->id;?>">
      <div id="more_link<?php echo $row->id;?>">
        <div class="report_reg_more"> <a href="#" class="rad" onclick="show_more('<?php echo $row->id;?>'); return false;">Read More</a> <a href="<?php echo url('sforms/add_comment');?>?type=paid&tid=<?php echo $row->id;?>">Add Comment</a> <a href="<?php echo url('sforms/view_comments_paid');?>?id=<?php echo $row->id;?>"><?php echo $num_comment;?> Comments</a> <span id="count<?php echo $row->id;?>_1"><?php echo $row->count;?></span> views </div>
      </div>
    </div>
    <!-- eof #less_d_num -->
    
    <div id="more_d_<?php echo $row->id;?>" style="display:none;">
      <div id="less_link<?php echo $row->id;?>">
        <div class="report_reg_more"> <a href="#" class="rad" onclick="show_less('<?php echo $row->id;?>'); return false;">Read less...</a> <a href="<?php echo url('sforms/add_comment');?>?type=paid&tid=<?php echo $row->id;?>">Add Comment</a> <a href="<?php echo url('sforms/view_comments_paid');?>?id=<?php echo $row->id;?>"><?php echo $num_comment;?> Comments</a> <span id="count<?php echo $row->id;?>_2"><?php echo $row->count;?></span> views </div>
      </div>
      <table class="details_table" width="100%" summary="this table has the details for a certain report.">
        <tr>
          <th width="20%"><span class="desc_lebel">Department:</span></th>
          <td><?php echo ($row->c_dept!=0)?$row->dept_name:getOthers($row->others_dept);?></td>
        </tr>
        <?php

					if(!empty($row->other_location))

					{

						?>
        <tr>
          <th><span class="desc_lebel">Office Location:</span></th>
          <td><?php echo $row->other_location;?></td>
        </tr>
        <?php

					}

					?>
        <tr>
          <th><span class="desc_lebel">Transaction:</span></th>
          <td><?php echo ($row->c_transaction!=0)?$row->trans_name:getOthers($row->others_transaction);?></td>
        </tr>
        <tr>
          <th><span class="desc_lebel">Bribe Type:</span></th>
          <td><?php echo $row->c_bribe_type;?></td>
        </tr>
        <tr>
          <th><span class="desc_lebel">Details:</span></th>
          <td><?php echo nl2br(strip_tags($row->c_addi_info));?></td>
        </tr>
      </table>
      <div class="clear"></div>
      <div class="share_tool_d">
        <?php

                

                   $share_url = urlencode($GLOBALS['base_url'].'/sforms/view_report_paid?id='.$row->id);

                ?>
        <a href="http://www.facebook.com/share.php?u=<?php echo $share_url;?>" target="_blank" class="facebook_share_view"></a> &nbsp;&nbsp; <a href="http://twitter.com/share?url=<?php echo $share_url;?>" target="_blank" class="tweet_share_view"></a> </div>
    </div>
    <div class="clear"></div>
  </div>
  <?php

}//eof while



echo theme('pager', NULL, variable_get('default_nodes_main', 10));

?>
  <?php

}//eof if to check numrows

else

{

	?>
  <div class="display_info"> No reports found. </div>
  <?php

}

?>
</div>
