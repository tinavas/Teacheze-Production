<?php
drupal_add_css('sites/all/modules/sforms/css/mod_relevant.css', 'core', 'all');
?>
<?php 
if($name!=null){
	if($result=='1')
		echo "'". $name . "' is added to database";
	else
		echo "Theres is an error adding the name '".$name."' to databse. Its there in the database already.";
		}
?>
<form action="add_names" method="post">
<input type="text" name="add_names" id="add_names" />
<br /><input type="submit" name="submit" value="submit" />
</form>
