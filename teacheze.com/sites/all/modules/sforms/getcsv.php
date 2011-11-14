<?php 
mysql_connect('localhost','root','');
mysql_select_db('ipab_new');
$query='SELECT * from bd_register_for_updates';
$result=mysql_query($query);
$email_array=array();
while($row=mysql_fetch_assoc($result)){
	array_push($email_array, $row['email']);
}

$fp=open('./emails.csv');
fputcsv($fp, $email_array);
fclose($fp);
?>