<script type="text/javascript">
function getTransactions(selected)
{
	var c_dept = $('#c_dept').val();
	var url = '<?php echo url('sforms/get_transactions');?>?others=false&all=true&selected='+selected+'&did='+c_dept;			
	$('#sub_transaction_con').load(url);
	
}

function show_hide_answers(id)
{
	if(document.getElementById(id).style.display=='none')
	{
		$('#'+id).fadeIn();
	}
	else
	{
		$('#'+id).fadeOut();
	}
}

function collapse_all()
{
	var cnt_tot = document.getElementById('cnt_tot').value;
    
	for(i=0;i<=cnt_tot;i++)
	{
		$('#ans_'+i).fadeOut();
	}
}

</script>

      
		<!--Slide-->
	<div class="innerhead headblog2">
        <img class="askraghu" src="<?php echo  url('sites/all/themes/ipab_client');?>/images/raghu.jpg" title="RAGHU" alt="RAGHU" />
        <div class="askraghu_text">
		I am here to take you through a guided tour of the MAZE how corruption is plotted and executed and to provide you with some answers to your questions on how to avoid, resist, reduce and eliminate corruption. With 26 years of experience in the Government of India and of Karnataka State, as a former high ranking civil servant, I bring you the view from within the "system"        
        </div>
	</div><!--End Slide-->


	<!--News Content--> 
	<div id="blog">
    <div class="blog_container divtab repot4 askr">

<form action="" class="search_form" method="get" name="myform">
		   
           <fieldset>
           
             <div class="ask">
          	<label>Departments</label><br />
		    <select class="form-select" name="c_dept" id="c_dept" onchange="getTransactions(0);">
        <option value="all">All</option>
        <?php echo $dept_options ;?>
			</select>             
            </div>
            
		  <div class="ask">
          <label>Transaction</label><br />
          <div id="sub_transaction_con" style="margin:0; padding:0">
            <select class="form-select" name="c_transaction" id="c_transaction">
                <option value="all">All</option>
            </select>
    	  </div>
    <?php
    if(!empty($_REQUEST['c_transaction']))
    {
     ?>
     <script type="text/javascript">
     getTransactions('<?php echo $_REQUEST['c_transaction'];?>');
     </script>
     <?php
    }
    ?>
    </div>			        	

           <div class="ask">
           <input type="hidden" name="fsubmit" value="fsubmit" />
         <input type="image" name="go"  src="<?php echo  url('sites/all/themes/ipab_client');?>/images/go.png" class="sub_bu" />
			</div>

</fieldset>
</form>

    <div class="askr_que">
<h1 class="c_head">Total Answers: <?php echo $num_recs;?> and counting...</h1>
<h3>click on question to open answers, click to close</h3>
<p><input type="button" name="collapse" onclick="collapse_all();return false;" value="Collapse All Answers" /></p>

<?php
$cnt_r = 0;
while ($row = db_fetch_object($result)) 
{
	$num_comment = get_num_comment('raghuans',$row->id);
?>
	<p class="question"> <a href="#" onclick="show_hide_answers('ans_<?php echo $cnt_r;?>'); return false;"> <strong>Q.</strong> <?php echo $row->c_question;?>?
    </a></p>
	<div id="ans_<?php echo $cnt_r;?>" style="display:none;">
	<span class="answer">A. </span><br /> <?php echo html_entity_decode($row->reply_ans);?>
    </div>

    <div class="report_reg_more">
        <a href="<?php echo url('sforms/add_comment');?>?type=raghuans&tid=<?php echo $row->id;?>">Add Comment</a> 
        <a href="<?php echo url('sforms/view_comments_raghu_answers');?>?id=<?php echo $row->id;?>"><?php echo $num_comment;?> Comments</a>        
    </div>
    
    <div class="clear"></div>
    <br />
<?php
$cnt_r++;
}//eof while

echo theme('pager', NULL, variable_get('default_nodes_main', 10));
?>
<input type="hidden" name="cnt_tot" id="cnt_tot" value="<?php echo $cnt_r-1;?>" />
</div><!-- #eof div #q_and_ans -->

</div><!-- #eof div #report_form --></div>