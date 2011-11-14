
<!--Slide-->
<div class="innerhead headblog2">
<img class="askraghu" src="<?php echo  url('sites/all/themes/ipab_client');?>/images/raghu.jpg" title="RAGHU" alt="RAGHU" />
<div class="askraghu_text">
I am here to take you through a guided tour of the MAZE how corruption is plotted and executed and to provide you with some answers to your questions on how to avoid, resist, reduce and eliminate corruption. With 26 years of experience in the Government of India and of Karnataka State, as a former high ranking civil servant, I bring you the view from within the "system"        
</div>
</div><!--End Slide-->

<div id="blog">
<div class="blog_container divtab repot4 askr">
    
<p><a href="<?php echo url('sforms/view_raghu_answers');?>" class="yellow_box">&lt;&lt; Back To Answers</a></p>
<br />
	
    
    <div class="report_reg">
		<p class="question"><h2> <strong>Q.</strong> <?php echo $row_det->c_question;?>?</h2></a></p>
		<div id="ans_<?php echo $cnt_r;?>" style="display:block;">
			<span class="answer">A. </span><br /> <?php echo html_entity_decode($row_det->reply_ans);?>
    	</div>
    </div> 

<div class="report_reg">
<a href="<?php echo url('sforms/add_comment');?>?type=raghuans&tid=<?php echo $row_det->id;?>" class="yellow_box">Add a comment</a>
<br /><br />

<?php
echo theme('view_vote_comments',$res_com);
?>
</div>

</div></div><!-- eeof report form -->