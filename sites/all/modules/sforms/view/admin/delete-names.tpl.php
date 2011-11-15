<?php
drupal_add_css('sites/all/modules/sforms/css/mod_relevant.css', 'core', 'all');
?>
<?php 
if($name!=null){
	if($result=='1')
		echo "'". $name . "' is deleted from database";
	else
		echo "Theres is an error deleting the name '".$name."' from databse. It might not be there in the database.";
		}
?>
<form action="delete_names" method="post">
<input type="text" name="delete_names" id="delete_names" />
<br /><input type="submit" name="submit" value="submit" />
</form>
