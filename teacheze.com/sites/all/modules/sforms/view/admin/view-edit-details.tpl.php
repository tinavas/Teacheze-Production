<?php
drupal_add_css('sites/all/modules/sforms/css/mod_relevant.css', 'core', 'all');

$row = db_fetch_object($result);//we have only one result
?>
<form name="edit_bribe" method="post" action="<?php echo url('admin/sforms/update_bribe');?>?id=<?php echo $row->id;?>">

<table width="100%">
    
    <tr class="even">
        <td>Title</td>
        <td><input type="text" name="c_name" id="c_name" value="<?php echo $row->c_name; ?>" size="63"></td>
    </tr>
    
    <tr class="even">
        <td>Additional Information</td>
        <td>
        <textarea rows="5" cols="60" name="addi_info"><?php echo $row->c_addi_info;?></textarea>
        </td>   
    </tr>
    
    <tr>
        <td colspan="2">
        <br />
        <input type="submit" value="Update"/>
        </td>
    </tr>
    
</table>
</form>
