<?php
include_once('thumbnail.inc.php');

class fileUpload
{

/*
papam -1 = $_FILES['field_name']
param -2 = new unique name of image - which we will use to rename
param -3 = path to the img folder
*/

function image_save($file,$new_name,$path=null){
	$ftmp = $file['tmp_name'];
	$oname = $file['name'];
	
	$new_name_path =  $path.$new_name;
	
	if(move_uploaded_file($ftmp, $new_name_path)){
	}
}

/*
papam -1 = filename - which is equal to new name i mean the name using which the file is got saved
param -2 = specify the width of the new resize image
param -3 = specify the height of the new resize image
param -4 = path to the img folder - if required
param- 5 = resized image prefix
param -6 = croping if required

*/

function resize($file, $width = null, $height = null, $path=null,$prefix=null,$crop = null){

	$filename = $path.$file;
    
    $new_size = fileUpload::getImageSizeForThumb($width,$height,$filename);
    
    if($new_size['resize_status']=='false' && !empty($prefix))    
    {
       return; 
    }
    
    $width  =  $new_size['new_width'];
    $height =  $new_size['new_height'];
    
  	$thumb = new Thumbnail($filename);
    
	if($crop!=""){
  	$thumb->cropFromCenter($crop);
	}
	if($width!="" && $height!=""){
  	$thumb->resize($width, $height);
	}
    $thumb->save($path.$prefix.$file);  
  
 }

/*
papam -1 = image_name - name of the image generally for understanding $_FILE['file']['name']
*/
 
	function getImageExtension($image_name)
	{
		
		$image_arr = explode(".",$image_name);
		
		$extension = $image_arr[count($image_arr)-1];
		
		return ".".$extension;
		
	}

/*
papam -1 = image_name - along with the absolute path.
*/	
	function deleteImage($image_name)
	{
		
		if(file_exists($image_name))
		{
			unlink($image_name);
		}
	
	} 
    
    function getImageSizeForThumb($toWidth,$toHeight,$originalImage)
    {
        
        list($width, $height) = getimagesize($originalImage);
        
        $new_size = array('new_width'=>$width,'new_height'=>$height,'resize_status'=>'true');
                
        if($toHeight>=$height && $toWidth>=$width) //no need to resize in this case
        {
            $new_size['resize_status'] = 'false';
            return $new_size;
        }
        
        $xscale=$width/$toWidth;
        $yscale=$height/$toHeight;// Recalculate new size with default ratio
                
        
        if ($yscale>$xscale){
            $new_size['new_width'] = round($width * (1/$yscale));
            $new_size['new_height'] = round($height * (1/$yscale));
        }
        else {
            $new_size['new_width'] = round($width * (1/$xscale));
            $new_size['new_height'] = round($height * (1/$xscale));
        }
        
        return  $new_size;
    }

}
?>