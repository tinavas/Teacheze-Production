<?php
if(mysql_num_rows($res_com)>0)
{
	$cnt = 1;
	while($row = db_fetch_object($res_com))
	{
		?>
		<div class="vote_comment" <?php echo ($cnt==1)?'style="border-top:1px solid #E8AD04;margin-top:10px;"':'';?>>
			<h2><?php echo $row->subject;?></h2>
			<p><?php echo $row->comment;?></p>
		</div>
		<div class="clear"></div>
		<?php
		$cnt++;
	}
}
else
{
	echo "<p>No comments have been posted, be the first to write a comment.</p>";
}
?>