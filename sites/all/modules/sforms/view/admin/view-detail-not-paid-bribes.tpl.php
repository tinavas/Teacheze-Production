<?php
drupal_add_css('sites/all/modules/sforms/css/mod_relevant.css', 'core', 'all');

$row = db_fetch_object($result);//we have only one result
?>
<h1>Viewing the details of :: <?php echo $row->c_name;?></h1>

<table width="100%">

	<tr class="even">
    	<td>City</td>
        <td>
        	<?php echo $row->c_city;?>
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
        
	<tr class="even">
    	<td>Bribe Resisted By</td>
        <td>
        	<span class="camel_later"><?php echo $row->c_bribe_resisted_by;?></span>
        </td>   
    </tr>    
    
    <tr class="even">
        <td>Date</td>
        <td><?php echo getDateFormat($row->created,'F j, Y - H:i');?></td>
    </tr>
    
    <tr class="even">
    	<td>Published</td>
        <td><strong><?php echo ($row->approved==0)?'No':'Yes'; ?></strong></td>
    </tr>
    
    <tr class="even">
    	<td>Additional Information</td>
        <td>
        	<?php echo nl2br($row->c_addi_info);?>
        </td>   
    </tr>
    
    <tr>
    	<td colspan="2">
        <br />
        	<input type="button" value="back" onclick="document.location.href='<?php echo url('admin/sforms/view_not_paid_bribes');?>';" />
            <input type="button" value="<?php echo ($row->approved==0)?'Publish It':'Unpublish It'; ?>" onclick="document.location.href='<?php echo url('admin/sforms/publish_unpublish');?>?type=notpaid&id=<?php echo $row->id;?>&published=<?php echo ($row->approved==0)?'1':'0'; ?>';" />
        </td>
    </tr>
    
</table>
