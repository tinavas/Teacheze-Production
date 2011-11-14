<?php
function add_bribe_art()
{
	if(isset($_REQUEST['fsubmit']))
	{
		$file_name = '';

		if(!empty($_FILES['image_file']['name']))
		{
			include_once(dirname(__FILE__).'/includes/file_upload.php');

			$path     = dirname(__FILE__).'/art_pics/';

			$file_name = time().$_FILES['image_file']['name'];

			fileUpload::image_save($_FILES['image_file'],$file_name,$path);

			fileUpload::resize($file_name, 600, 600, $path,'');

			fileUpload::resize($file_name, 150, 150, $path,'tn_');

		}

		$date_time = date('Y-m-d H:i:s');

		$nid = db_insert('bd_bribe_art',
		array(
		'image_url' => $file_name,
		'image_title' => filtertxtb($_REQUEST['image_title']),
		'image_desc' => filtertxtb($_REQUEST['image_desc']),
		'date_time' => $date_time,
		'IP' => $_SERVER['REMOTE_ADDR'],
		));

		return '<div class="display_success">

                Thank you for submitting the art, our administrator will review and publish it.
                
                <script type="text/javascript">
                    setTimeout("document.location.href=\''.$redurl.'\';","100");
                </script>
                
                </div>

                ';    

	}
	else
	{
		return theme('add_bribe_art');
	}
}

function filtertxtb($str)
{
	// Ensure it's a string
	$str = strval($str);
	// We strip all html tags
	$str = strip_tags($str);

	// Remove any whitespace
	//$str = preg_replace('/\s\s+/',' ', $str);

	return $str;
}

function view_bribe_arts()
{
	$sql = "SELECT * from bd_bribe_art WHERE approved='1' order by date_time desc";

	$res =  db_query($sql);

	$num_recs = mysql_num_rows($res);

	$queryResult = pager_query(db_rewrite_sql($sql), 25);

	return theme('view_bribe_arts',$queryResult,$num_recs);
}

function full_view_bribe_art()
{
	$page_num = $_REQUEST['pn'];

	$sql_cnt = "SELECT * from bd_bribe_art WHERE approved='1' order by date_time desc";

	$res_cnt =  db_query($sql_cnt);

	$num_recs = mysql_num_rows($res_cnt);

	$num_recs = $num_recs - 1;

	$next_page =  $page_num + 1;

	if($page_num==$num_recs)
	{
		$next_page = 0;
	}

	$prev_page =  $page_num - 1;

	if($prev_page<0)
	{
		$prev_page = $num_recs;
	}

	$sql = "SELECT * from bd_bribe_art WHERE approved='1' order by date_time desc LIMIT $page_num,1";

	$res =  db_query($sql);

	$row = db_fetch_object($res);

	//get the comments

	$sql_comments = "SELECT * from bd_bribe_art_comments WHERE art_id='".$row->id."'";

	$res_comments =  db_query($sql_comments);

	return theme('full_view_bribe_art',$row,$res_comments,$next_page,$prev_page);
}

function add_bribe_art_comments()
{
	$page_num = $_REQUEST['pn'];

	$redurl = url('bribeart/full_view_bribe_art').'?pn='.$page_num;

	if(isset($_REQUEST['fsubmit']))
	{
		$art_id = $_REQUEST['art_id'];

		$date_time = date('Y-m-d H:i:s');

		db_insert('bd_bribe_art_comments',
		array(
		'comment_text' => filtertxtb($_REQUEST['comment_text']),
		'art_id' => $art_id,
		'date_time' => $date_time,
		'IP' => $_SERVER['REMOTE_ADDR'],
		));



		return '<div class="display_success">

                Thank you for submitting the comment.
                
                </div>

                <script type="text/javascript">
                    setTimeout("document.location.href=\''.$redurl.'\';","100");
                </script>
                    
                ';  


	}
	else
	{
		drupal_goto('bribeart/view_bribe_arts');
	}


}
?>