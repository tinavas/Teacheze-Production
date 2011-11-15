<?php	
global $user, $base_url;
$transactions = $base_url."/sites/all/themes/ulearn_4/images/history.png";
$package 	= $base_url."/sites/all/themes/ulearn_4/images/package.png";
$userimg 	= $base_url."/sites/all/themes/ulearn_4/images/user.png";
?>
<div style="width:100%; padding:10px; float:left;">
<table width="100%" border="0">
  <tr>
    <td width="220" align="center" valign="middle">
    <?php
   		echo '<a href="'.$base_url.'/?q=teachers"><img title="Teachers" src="'.$userimg.'"></a>';
	?>
    </td>
    <td width="15">&nbsp;</td>
    <td width="241" align="center" valign="middle">
	<?php
	if(user_access('administer')) {
		echo '<a href="'.$base_url.'/?q=admin/ulearn/transactions"><img title="Transactions" src="'.$transactions.'"></a>';
	} else {
		echo '<a href="'.$base_url.'/?q=transactions"><img title="Transactions" src="'.$transactions.'"></a>';
	}
    ?></td>
  </tr>
  <tr>
    <td align="center" valign="middle">Teachers</td>
    <td>&nbsp;</td>
    <td align="center" valign="middle">Transactions</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle">
	<?php 
	if ( $user->user_selectable_roles == 4 ) {
		echo '<a href="'.$base_url.'/?q=edit_package"><img title="Edit My Packages" src='.$package.'></a>';
	}
	?>
    </td>
    <td>&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle">
	<?php if ( $user->user_selectable_roles == 4 ) {?>
	    Edit My Packages
	<?php		
	}
	?></td>
    <td>&nbsp;</td>
    <td align="center" valign="middle">
    <?php
    if ($result->paid != 0) {    
		echo '<a href="'.$base_url.'?q=node/10">Enter Class Room</a>';
	}
	?>
    </td>
  </tr>
</table>
</div>
