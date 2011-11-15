<?php
function t_parse($Para)
{
	//return true;
	
	$reg_exp='/\b[0-9]{8,50}\b/siU';	// numbers >10 and <50 digits.. If you want exactly 10, you can add \b at the beginning and end
		$return=preg_replace($reg_exp,' ******* ', $Para);
		$Para=$return;
	
	$names_array = array('/\bravi\b/', '/bdharam\b/');

	
	$cue_word_array=array("Mr","Ms","Mrs"); // Insert Required Cue Words
	
	foreach ($cue_word_array as $cue_word)
	 {
	 	if(!($cue_word==strtolower($cue_word))){
		array_push($cue_word_array,strtolower($cue_word));
		}
		if(!($cue_word==strtoupper($cue_word))){
		array_push($cue_word_array,strtoupper($cue_word));
		}
	} 
	// loop for removing Names following cuewords
	foreach ($cue_word_array as $cue_word)
	{
		$reg_exp='/\b'.$cue_word.'[\.|\ ].[A-Za-z]{2,100}\b/sU';	
		
		if (preg_match_all($reg_exp,$Para, $Names)) 
		{
			  foreach ($Names as $Name)	
			  {
			  	foreach($Name as $Name_)
			  	{
				  	$OnlyName=explode($cue_word,$Name_);
				  	if($OnlyName[1][0]==".")
				  		$OnlyName[1][0]=" ";
				  	

				  	$OnlyName[1]='/\b'.$OnlyName[1].'\b/siU';;
				  	$Para=preg_replace($OnlyName[1], " ******* ", $Para);
				  	}
			  }
		}	

	}
	
	$namesArray=array();
	
	$sql_names = "SELECT name from bd_filter_names";
	
	$name_res =  db_query($sql_names);
	
	
	while($row_name = db_fetch_object($name_res))
	{
		$row_new_data ='/\b'.$row_name->name.'\b/siU';;
		array_push($namesArray, $row_new_data);
	}
	
	//function to remove dictionary words
	/*
	mysql_connect("localhost","root","");
	mysql_select_db("ipab_new");
	$namesArray=array();
	$result=mysql_query("SELECT * from names");
	while($row=mysql_fetch_assoc($result)){
		$row['name']='/\b'.$row['name'].'\b/siU';;
		array_push($namesArray, $row['name']);
	}
	*/
	
	$Para=preg_replace($namesArray, "**********", $Para);
	
	//Removing the last character if the first word is in caps
	$Para=trim($Para);
	$Words=explode(" ",$Para);
	$LastWord=$Words[count($Words)-1];
	if(preg_match("/[A-Z]/sU",$LastWord))
		$Words[count($Words)-1]="*******";
		$Para=implode(" ", $Words);
	return($Para);		
}

//Usage sample

/*
$str="Run your code on this: I paid a bribe of Rs.500.00 to the constable who came to my house for passport renewal verification. 
When I asked why should I pay, his answer was that if I donot pay, the file will not move and gather dust till the amount is paid.
Initially he was asking for Rs.1500.00, but knowing that my father was a retired pathologist from central government, and I too was a central government employee before leaving the job, he settled for,  Thanks ";
$new_str=t_parse($str);
echo "<pre>".$new_str."</pre>";
*/

?>