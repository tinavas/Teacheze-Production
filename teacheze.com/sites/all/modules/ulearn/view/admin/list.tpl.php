<a href="<?php echo url('admin/ulearn');?>" style="float:right;">Back to Dashboard</a><br clear="right" />
<div>
	<table>
    <tr>
    	<th valign="baseline">Picture</th>
        <th>Username</th>
        <th>Status</th>
        <th>Edit</th>
    </tr>
    <?php 
	global $base_url;
	if ( !empty ($result) ) : 
		foreach ($result as $val) :
			$status = ($val->status == 1) ? 'active' : 'block';
			echo '<tr>
				<td><img src="'.$base_url.'/'.$val->picture.'" style="vertical-align:middle; height:80px; width:80px;" /></td>
				<td><a href="'.$base_url.'/user/'.$val->uid.'">'.ucwords ($val->name).'</a></td>
				<td>'.$status.'</td>
				<td><a href="?q=user/'.$val->uid.'/edit&destination=admin/ulearn/list/'.$val->rid.'">Edit</a></td>
			</tr>';
		endforeach;
	endif; 
	?>
    </table>
</div>

