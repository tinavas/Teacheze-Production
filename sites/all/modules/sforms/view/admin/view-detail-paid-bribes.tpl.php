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
                        
    <tr class="even">
    	<td>Transaction</td>
        <td>
        	<?php
				echo ($row->c_transaction!=0)?$row->trans_name:getOthers($row->others_transaction);
			?>
        </td>
    </tr>
    
	<tr class="even">
    	<td>Amount Paid</td>
        <td>
        	<?php echo $row->c_amt_paid;?>
        </td>   
    </tr>
    
	<tr class="even">
    	<td>Date Paid</td>
        <td>
        	<?php echo $row->c_date_paid;?>
        </td>   
    </tr>
    
	<tr class="even">
    	<td>Bribe Type</td>
        <td>
        	<span class="camel_later"><?php echo $row->c_bribe_type;?></span>
        </td>   
    </tr>    
    
    <tr class="even">
    	<td>Transaction Value</td>
        <td>
        	<?php echo $row->c_val_tran;?>
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
        	<input type="button" value="back" onclick="document.location.href='<?php echo url('admin/sforms/view_paid_bribes');?>';" />
            <input type="button" value="<?php echo ($row->approved==0)?'Publish It':'Unpublish It'; ?>" onclick="document.location.href='<?php echo url('admin/sforms/publish_unpublish');?>?type=paid&id=<?php echo $row->id;?>&published=<?php echo ($row->approved==0)?'1':'0'; ?>';" />
  			
            <input type="button" value="edit" onclick="document.location.href='<?php echo url('admin/sforms/edit_bribe');?>?type=paid&id=<?php echo $row->id;?>';" />                      
        </td>
    </tr>
    
</table>
