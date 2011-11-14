
<div class="innerhead">
<span><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/bribe-art-head.png" /></span>

<a href="<?php echo url('bribeart/add_bribe_art');?>">
<img src="<?php echo url('sites/all/themes/ipab_client/');?>images/add-new-image.png" />

</a>

</div>
              
<div id="blog">
<div class="blog_container">
    
<?php

if(mysql_num_rows($result)>0)
{
	?>

<h1 class="c_head">Total arts: <?php echo $num_recs;?> and counting...</h1>
<table width="100%" cellpadding="5" cellspacing="5">
<?php
$cnt_r = 0;
while ($row = db_fetch_object($result)) 
{
    if($cnt_r==0)
    {
        echo '<tr>';
    }
    else if($cnt_r%3==0)
    {
        echo '</tr><tr>';
    }
    
?>
    <td>
        
        <a href="<?php echo url('bribeart/full_view_bribe_art');?>?pn=<?php echo $cnt_r;?>">
        <img src="<?php echo url('sites/all/modules/bribeart/art_pics/tn_'.$row->image_url);?>" alt="<?php echo $row->image_title;?>" class="artimgimg" />
        </a>
        <div class="art_title"><?php echo $row->image_title;?></div>
    </td>
    
<?php
$cnt_r++;
}//eof while
?>
</tr>
</table>
<?php
echo theme('pager', NULL, variable_get('default_nodes_main', 10));
?>

<?php
}//eof if to check numrows
else
{
	?>
    <div class="display_info">
    No reports found.
    </div>
    <?php
}
?>

</div>
</div><!-- #eof blog -->