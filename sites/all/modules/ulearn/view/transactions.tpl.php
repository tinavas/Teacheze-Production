<table cellpadding="5" cellspacing="2" width="100%">
	<tr>
    	<td colspan="2">
        <form name="search" action="<?php echo url('transactions');?>" method="post">
            <input type="text" name="name" id="name" size="25" value="Enter Name ..." onclick="if (document.getElementById('name').value == 'Enter Name ...') { document.getElementById('name').value=''}" onblur="if (document.getElementById('name').value == '') { document.getElementById('name').value='Enter Name ...'}" />
            <input type="submit" name="submit" value="Search" />
        </form>
        </td>
        <td colspan="2"><a href="<?php echo url('transactions');?>">Reset</a></td>
        <td colspan="3">
        <a href="<?php echo url('dashboard');?>" style="float:right;">Back to Dashboard</a><br clear="right" /><br />
        </td>
    </tr>
	<tr>
    	<th>Name</th>
    	<th>Course Type</th>
        <th>Subject</th>
        <th>Date</th>
        <th>Time<br />(24hrs)</th>
        <th>Duration</th>
        <th>Payment</th>
    </tr>
    <?php 
	while ($row = db_fetch_object ($result) ) : 
	
		//echo "<PRE>"; print_r ($row); //exit;
	
		if ($row->payment_status == 0) $status = 'Pending';
		if ($row->payment_status == 1) $status = 'Completed';
		if ($row->payment_status == 2) $status = 'Cancelled';
	?>
    <tr>
    	<td><a href="<?php echo url('user/'.$row->uid);?>"><?php echo ucwords ($row->uname); ?></a></td>
    	<td>
		<?php
		if ($row->lesson_type == 'I') {
			echo 'Individual Lesson';
		} else if ($row->lesson_type == 'G') {
			echo 'Group Lesson';
		} else {
			echo $row->package_id . ' Lessons Package';
		}
		?>
        </td>
        <td><?php echo $row->subject; ?></td>
        <td>
		<?php 
		if ( empty ($row->package_id) ) {
			echo date ('M d, Y', strtotime ($row->booked_on_date)); 
		} else {
			echo '<strong>from</strong> ' . date ('M d, Y', strtotime ($row->booked_on_date)) . '<br /><strong>to</strong> ' . date('M d, Y', strtotime ( date("d-m-Y", strtotime($row->booked_on_date)) . " + " . $row->package_id . " day"));
		}
		?>
        </td>
        <td><?php echo str_pad ($row->from_hrs, 2, 0, STR_PAD_LEFT) . ' - ' . str_pad ($row->from_mins, 2, 0, STR_PAD_LEFT); ?></td>
        <td><?php echo $row->duration_mins; ?> mins</td>
        <td><?php echo $status; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php echo theme('pager', NULL, variable_get('default_nodes_main', 10)); ?>