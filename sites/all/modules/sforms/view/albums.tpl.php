
		<link rel="stylesheet" href="<?php echo  url('sites/all/modules/sforms/');?>css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
		<script src="<?php echo  url('sites/all/modules/sforms/');?>js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>

 <script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
		$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'fast',slideshow:10000});
		});
</script>
    <script type="text/javascript" src="<?php echo  url('sites/all/themes/ipab_client/');?>/fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
	<script type="text/javascript" src="<?php echo  url('sites/all/themes/ipab_client/');?>/fancybox/jquery.fancybox-1.3.1.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo  url('sites/all/themes/ipab_client/');?>/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
    <script type="text/javascript">
	$(document).ready(function(){	
		$("#various3").fancybox({
			'width'				: 960,
			'height'			: 600,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'				: 'iframe'
		});
		
		$("#various1").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});				
	});	
	</script>

        
		
		<style type="text/css" media="screen">
			
			.wide {
				border-bottom: 1px #000 solid;
				width: 4000px;
			}
			.gallery li{ display:inline; list-style:none; margin-right:6px; margin-bottom:10px; float:left;
			}
			* { margin: 0; padding: 0; }
			.pp_gallery ul a img { border: 0; height:55px!important; }
			.gallery li img{padding:0px;}
			.abh4{font-size:14px; font-weight:bold; color:#514040;}
			.aphoto{background:none repeat scroll 0 0 #FFFFFF;
border:1px solid #CCCCCC;
display:inline-block;
outline:medium none;
padding:4px;
position:relative;
vertical-align:bottom;}
.aphoto img{
	background-color:#EEEEEE;
background-position:center 25%;
background-repeat:no-repeat;
display:block;}
			
		</style>
<div style="display: none;">
		<div id="inline1" style="width:500px;height:150px;overflow:auto;">
        

<div id="msg_email" style="padding:5px 5px 5px 30px; color:#f00; font-weight:bold;"></div>

<div id="form_reg_s">

Dear user, in order to post images you have to go to our facebook page and post on our wall. The images will automatically appear here.
Please refrain from posting copyrighted, irrelevant, or offensive images.
<br /><br />
<br />
<br />
<br />
<a href="http://www.facebook.com/ipaidabribe"  target="_blank" style=" color:#990000;">Click here to proceed</a>
</div>

</div>
</div>
		<!--Slide-->
	<div class="innerhead">
	<span><img src="<?php echo url('sites/all/themes/ipab_client/images/albums.png');?>" /></span>
    <a href="#inline1" id="various1"><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/add-photos.png" title="add photos" /></a>
	</div><!--End Slide-->

	<!--News Content--> 
	<div id="blog">
    <div class="blog_container">
    
<ul class="gallery clearfix" style="display:none;"></ul>
<h4 class="abh4">

<?php
echo $albumTitle;
?>
</h4>
<ul class="gallery clearfix">
<?php
$count= count($thumbs);
for($i=0;$i<$count;$i++)
{
?>
			<li><a class="aphoto" href="<?php echo $thumbs[$i];?>" rel="prettyPhoto[gallery2]"><img src="<?php echo $images[$i];?>" alt="ipaidabribe" width="130" height="54" /></a></li>
<?php
}
?>
</ul>
    
</div>   
</div><!--End News Content-->