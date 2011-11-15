
<?php

if(mysql_num_rows($result)>0)
{
?>
<br />

<h1 class="c_head">Total arts: <?php echo $num_recs;?> and counting...</h1>

<br />

<form method="post" action="">
<strong>With selected </strong> 
<select name="action_d">
    <option value="1">Publish</option>
    <option value="0">Un Publish</option>
    <option value="3">Delete</option>
</select>
<input type="hidden" name="asubmit" value="asubmit" />
<input type="submit" name="update" value="Update" />

<br /> <br /> 
                               
<table width="100%" cellpadding="5" cellspacing="5">
<tr>
    <th>Select</th>
    <th>Image Title</th>
    <th>Image Desc</th>
    <th>Image</th>
    <th>Published</th>
    <th>Delete</th>
</tr>
<?php
$cnt_r = 0;
while ($row = db_fetch_object($result)) 
{
     
?> 
    <tr>
    <td><input type="checkbox" name="sids[]" id="sid_<?php echo $cnt_r;?>" value="<?php echo $row->id;?>" /></td>
    <td><?php echo $row->image_title;?></td> 
    <td><?php echo $row->image_desc;?></td>
    <td>        
        <img src="<?php echo url('sites/all/modules/bribeart/art_pics/tn_'.$row->image_url);?>" alt="<?php echo $row->image_title;?>" />
    </td>
    <td>
        <a href="<?php echo url('admin/bribeart/publish_unpublish');?>?type=image&id=<?php echo $row->id;?>&published=<?php echo ($row->approved==0)?'1':'0'; ?>"><strong><?php echo ($row->approved==0)?'No':'Yes'; ?></strong></a>
    </td>
    <td>
    <a href="<?php echo url('admin/bribeart/delete_art');?>?type=image&id=<?php echo $row->id;?>" onclick="return confirm('Are you sure you want to delete the record.')">Delete</a>
    </td>
    </tr> 
<?php
$cnt_r++;
}//eof while
?>
</table>
</form>
<?php
echo theme('pager', NULL, variable_get('default_nodes_main', 10));
?>

<?php
}//eof if to check numrows
else
{
	?>
    <div class="display_info">
    No reports found.
    </div>
    <?php
}
?>

</div>
</div><!-- #eof blog -->