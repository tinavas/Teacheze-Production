<a href="<?php echo url('admin/sforms/get_update_emails');?>?csvdwd=yes">Download</a>
<table>
<tr>
    <th>Email</th>
</tr>
<?php
$cnt_r = 0;
while ($row = db_fetch_object($result)) 
{
?>
	<tr class="<?php echo ($cnt_r%2==0)?'odd':'even';?>">
        <td><?php echo $row->email;?></td>  
    </tr>
<?php
$cnt_r++;
}//eof while
?>
</table>