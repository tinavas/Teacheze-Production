<?php 
global $base_url; 
$noimg = $base_url."/sites/all/themes/ulearn_4/images/noimg.png";
?>

<a href="<?php echo url('dashboard');?>" style="float:right;">Back to Dashboard</a><br clear="right" /><br />
<table cellspacing="0" cellpadding="5" border="0">
<tbody>
  <?php while ($row = db_fetch_object ($result)) : ?>
  <tr>
    <td class="listingbar" colspan="2">
        <a href="<?php echo $base_url.'/user/'.$row->uid;?>">
            <b><?php echo ucwords ($row->name);?></b>
        </a>
    </td>
    <td class="listingbar">Packages</td>
  </tr>
  <tr>
    <td valign="top" width="100">
        <a href="<?php echo $base_url.'/user/'.$row->uid;?>"> 
        <?php 
		if( $row->picture != ""){
		?>
            <img width="85" height="64" title="<?php echo ucwords ($row->name);?>" src="<?php echo $base_url . '/' . $row->picture;?>" /> 
        <?php
		}else{
		?>
        	<img width="85" height="64" title="Image Not Available" src="<?php echo $noimg ;?>" /> 
        <?php
		}
		?>
        </a>
    </td>
    <td>
     <?php
      $profileVals = array ();
      $sql = db_query ("SELECT * FROM {profile_values} AS pv LEFT JOIN {profile_fields} AS pf ON pf.fid = pv.fid WHERE pv.uid = " . $row->uid);
      while ( $res = db_fetch_object ($sql) ) :
            $profileVals [] = $res;	
      endwhile;
	  
      //echo "<PRE>"; print_r ( $profileVals ); exit;
      
      echo '<table>';
        
      echo isset ($profileVals[6]->value) ? '<tr><td width="220"><b>'.$profileVals[6]->title.':</b></td><td>'.$profileVals[6]->value.'</td></tr>' : '';	
        
      echo isset ($profileVals[7]->value) ? '<tr><td><b>'.$profileVals[7]->title.':</b></td><td>'.$profileVals[7]->value.'</td></tr>' : '';	

      /*echo isset ($profileVals[8]->value) ? '<tr><td><b>'.$profileVals[8]->title.':</b></td><td>'.$profileVals[8]->value.'</td></tr>' : '';	*/

      echo isset ($profileVals[9]->value) ? '<tr><td><b>'.$profileVals[9]->title.':</b></td><td>'.$profileVals[9]->value.'</td></tr>' : '';	

      echo isset ($profileVals[2]->value) ? '<tr><td><b>'.$profileVals[2]->title.':</b></td><td>'.$profileVals[2]->value.'</td></tr>' : '';	

      echo isset ($profileVals[3]->value) ? '<tr><td><b>'.$profileVals[3]->title.':</b></td><td>'.$profileVals[3]->value.'</td></tr>' : '';	
      echo '</table>';
      ?> 
      <a href="<?php echo url('student_booking');?>?uid=<?php echo $row->uid;?>">Available Time</a> 
    </td>
    <td>
    	<?php
	/*	$packages = array ();
		$sql = db_query ("SELECT * FROM {packages} WHERE userid = " . $row->uid);
		while ( $res = db_fetch_object ($sql) ) :
			$packages [] = $res;	
		endwhile;
		
		foreach ($packages as $pack) {
			echo '<a href="view_package?lid='.$pack->id.'">'.$pack->p_title.'</a> <br />';
		}*/
		?>
    </td>
  </tr>
    
  <?php endwhile; ?>
</tbody>
</table>
<?php echo theme('pager', NULL, variable_get('default_nodes_main', 10)); ?>