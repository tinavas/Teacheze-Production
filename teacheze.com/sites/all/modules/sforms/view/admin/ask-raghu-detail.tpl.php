<?php
drupal_add_css('sites/all/modules/sforms/css/mod_relevant.css', 'core', 'all');
//drupal_add_js('sites/all/themes/ipab_client/nicedit/nicEdit.js', 'core', 'header');
drupal_add_js('sites/all/modules/sforms/js/ckeditor/ckeditor.js', 'core', 'header');

$row = db_fetch_object($result);//we have only one result
?>
<script type="text/javascript">
/*
bkLib.onDomLoaded(function() {
	new nicEditor().panelInstance('reply_ans');
	//new nicEditor({fullPanel : true}).panelInstance('area2');
});
*/
</script>

<form action="" method="post">
<table width="100%">

	<tr class="even">
    	<td colspan="2">
        <p><strong>Question</strong></p>
        <p><?php echo nl2br($row->c_question);?></p>
        </td>   
    </tr>
    
	<tr class="even">
    	<td>Department</td>
        <td>
        	<?php
				echo ($row->c_dept!=0)?$row->dept_name:getOthers($row->others_dept);
			?>
        </td>   
    </tr>
    
    <tr class="even">
    	<td>Transaction</td>
        <td>
        	<?php
				echo ($row->c_transaction!=0)?$row->trans_name:getOthers($row->others_transaction);
			?>
        </td>
    </tr>
        
<!--    <tr class="even">
    	<td>Published</td>
        <td><strong><?php echo ($row->approved==0)?'No':'Yes'; ?></strong></td>
    </tr>-->
    
    <tr class="even">
    	<td>Reply Text</td>
        <td>
        	<textarea rows="6" cols="70" name="reply_ans" id="reply_ans"><?php echo $row->reply_ans;?></textarea>
   			<script type="text/javascript">
			//<![CDATA[

				CKEDITOR.replace( 'reply_ans',
					{
						fullPage : false
					});

			//]]>
			</script>
        </td>   
    </tr>
    
    <tr class="even">
    	<td>Publish</td>
        <td>
        	<input type="checkbox" name="approved" value="1" <?php echo ($row->approved==1)?'checked="checked"':''; ?> />
        </td>   
    </tr>
    
    <tr>
    	<td colspan="2">
        <br />
        	<input type="submit" name="submit" value="Save" />

			<input type="hidden" name="id" value="<?php echo $row->id;?>" />
            
        	<input type="button" value="back" onclick="document.location.href='<?php echo url('admin/sforms/ask_raghu_list');?>';" />
            <!--<input type="button" value="<?php echo ($row->approved==0)?'Publish It':'Unpublish It'; ?>" onclick="document.location.href='<?php echo url('admin/sforms/publish_unpublish');?>?type=raghu&id=<?php echo $row->id;?>&published=<?php echo ($row->approved==0)?'1':'0'; ?>';" />-->
        </td>
    </tr>
    
</table>
</form>