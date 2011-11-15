<?php
function admin_ulearn_dashboard () {
	return theme('admin_ulearn_dashboard');
}

function admin_ulearn_settings () {
	if(isset($_REQUEST['submit'])) {
		if ( $_REQUEST['id'] ) {
			$where['id'] = $_REQUEST['id'];
			$sql = db_update('settings',
			array(
			'paypal' => $_REQUEST['paypal'],
			'percent' => $_REQUEST['percent'],
			'mode' => $_REQUEST['mode'],
			),
			$where
			);
		} else {
			$sql = db_insert('settings',
			array(
			'paypal' => $_REQUEST['paypal'],
			'percent' => $_REQUEST['percent'],
			'mode' => $_REQUEST['mode'],
			)
			);
		}

		drupal_set_message('Settins are Successfully saved');
		drupal_goto('admin/ulearn');
	} else {
		$res = db_fetch_object (db_query("SELECT * from {settings} WHERE id = 1"));
		return theme('admin_ulearn_settings', $res);
	}
}

function admin_ulearn_list () {
	if (arg(3)) {
		$where 	= " WHERE ur.rid = " . arg(3);
	} else {
		$where = '';
	}
	$res 	= db_query("SELECT * FROM {users} AS u LEFT JOIN {users_roles} AS ur ON ur.uid = u.uid " . $where);
	while ($row = db_fetch_object ($res)) {
		$result[] = $row;
	}
	return theme('admin_ulearn_list', $result);
}

function admin_ulearn_transactions () {
	$sql = "SELECT s.*, u.name AS tname, u.uid, sub.subject AS subject
			FROM student_booking s
			LEFT JOIN ul_users AS u ON u.uid = s.tid
			LEFT JOIN teacher_subjects_master AS sub ON sub.sub_id = s.subject_id
			ORDER BY s.tid";
	$res = pager_query($sql, 10);
	return theme('admin_ulearn_transactions', $res);
}
?>
