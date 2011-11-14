<?php
    drupal_add_js('sites/all/modules/sforms/js/validation.js', 'core', 'header');
?>

<script type="text/javascript">
function validate_m()
{
    msg = '';
    
    show_alert = true;
    
    checkEmptyAll('comment_text','Please enter your comment.');
            
    return showMessage();
}
</script>

<div class="innerhead">
<span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/bribe-art-head.png" /></span>

<a href="<?php echo url('bribeart/add_bribe_art');?>">
<img src="<?php echo url('sites/all/themes/ipab_client/');?>images/add-new-image.png" />
</a>

</div>
              
<div id="blog">
<div class="blog_container divtab">
    
<?php
$cnt_r = 0;

   
?>

<div>
<h1 class="bribearth1"><?php echo $row->image_title;?></h1>
<h1 class="bribearth2"><a href="<?php echo url('bribeart');?>">Back to album</a></h1>
</div>

<div class="bribeartthb">
<img  src="<?php echo url('sites/all/modules/bribeart/art_pics/'.$row->image_url);?>" alt="<?php echo $row->image_title;?>" class="artimgimg" />
</div>


<div class="clear"></div>
<div class="next_prev_i">
<a href="<?php echo url('bribeart/full_view_bribe_art');?>?pn=<?php echo $prev_page;?>">Prev</a> 
<a href="<?php echo url('bribeart/full_view_bribe_art');?>?pn=<?php echo $next_page;?>">Next</a>
</div>

<div id="bribe_art_comments">

<div id="validation_errors" style="display:none;">
</div>
    
<form name="comment_art" action="<?php echo url('bribeart/add_bribe_art_comments');?>" method="post" onsubmit="return validate_m();">
<p class="row"><label>Comment</label></p>
<p>
    <textarea cols="40" rows="5" name="comment_text" id="comment_text"></textarea>
</p>
<p>
<input type="hidden" name="fsubmit" value="fsubmit" />
<input type="hidden" name="art_id" value="<?php echo $row->id;?>" /> 
<input type="hidden" name="pn" value="<?php echo $_REQUEST['pn'];?>" />
<input type="image" src="<?php echo url('sites/all/themes/ipab_client/');?>images/post_btn.png" name="submit" value="Add Comment" class="sub_bu" />
</p>
</form>

<p>Showing <?php echo mysql_num_rows($res_comments);?> comments</p> 


<?php    
if(mysql_num_rows($res_comments) > 0)
{
    while($crow=db_fetch_object($res_comments))
    {
        ?>
        <div class="comment_row">
            <h3>Added on <?php echo $crow->date_time;?></h3>
            <p><?php echo $crow->comment_text;?></p>
        </div>
        <?php      
    }

}
else
{
    ?>
    No comments has been added, be the first one to add your comment.
    <?php
}
?>

</div>

<div class="clear"></div>
</div>
</div><!-- #eof blog -->