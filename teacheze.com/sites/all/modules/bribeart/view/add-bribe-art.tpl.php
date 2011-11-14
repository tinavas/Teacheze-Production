<?php
    drupal_add_js('sites/all/modules/sforms/js/validation.js', 'core', 'header');
?>

<script type="text/javascript">
function validate_m()
{
    msg = '';
    
    checkEmptyAll('image_title','Please enter image title');
    checkEmptyAll('image_desc','Please enter description.');
    if(checkEmptyAll('image_file','Please upload an image.'))
    {
        checkFileExt('image_file','image','Please select a valid image file. (PNG, JPEG, GIF) are allowed');
    }
            
    return showMessage();
}
</script>

<div class="innerhead">
<span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/bribe-art-head.png" /></span>

<a href="<?php echo url('bribeart');?>">
<img src="<?php echo url('sites/all/themes/ipab_client/');?>images/artb-images.png"  />
</a>

</div>
              
<div id="blog">
<div class="blog_container divtab">

    <div id="validation_errors" style="display:<?php echo (empty($msg))?'none':'block';?>;">
    <?php
        echo $msg;
    ?>
    </div>
    
    <form action="" method="post" enctype="multipart/form-data" onsubmit="return validate_m();">
        <p class="row"><label for="image_title">Image Title</label></p>
        <p class="row">
            <input type="text" name="image_title" id="image_title" value="" />
        </p>
        
        <p class="row"><label for="image_desc">Image Description</label></p>
        <p class="row">
            <textarea name="image_desc" id="image_desc" rows="10" cols="50" onkeypress="return limitChars(this.id,1000,'info_char');"></textarea>
        </p>
        <p><span id="info_char"></span></p>
        
        <p class="row"><label for="image_desc">Upload File</label></p>
        <p class="row">
        <input type="file" name="image_file" id="image_file" style=" background:#D2D1B3; color:#514040!important;" />
        </p>
        
        <p class="row">
            <input type="hidden" name="fsubmit" value="fsubmit" />
            <input type="image" src="<?php echo url('sites/all/themes/ipab_client/');?>images/submit.png" name="submir" value="Submit" class="sub_bu" />
        </p>
                
    </form>

</div>
</div><!-- #eof blog -->