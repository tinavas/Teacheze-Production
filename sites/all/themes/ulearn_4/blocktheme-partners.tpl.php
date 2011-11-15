<div class="art-block clear-block block block-<?php print $block->module ?>" id="block-<?php print $block->module .'-'. $block->delta; ?> <?php print $block_classes; ?> floating">
    <div class="floating">

	<?php if (!empty($block->subject)): ?>
<div class="art-blockheader">
		    <div class="l"></div>
		    <div class="r"></div>
		     <div class="t">
			<h2 class="subject"><?php echo $block->subject; ?></h2>
</div>
		</div>

	<?php endif; ?>
<div class="art-blockcontent content">
	    <div class="art-blockcontent-body">
	<!-- block-content -->

		<?php // echo $block->content; ?>

		<div class="featured-image">
            <img src="<?php echo $base_path . $directory; ?>/reskin/css/images/students.jpg" alt="" />
        </div>
        <ul class="logos">
            <li><a href="http://www.acels.ie/" target="_blank"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/logo-1.png" alt="" /></a></li>
            <li><a href="http://www.mei.ie/" target="_blank"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/logo-2.png" alt="" /></a></li>
            <li><a href="http://www.ulearn.ie/english_courses_dublin" target="_blank"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/logo-3.png" alt="" /></a></li>
            <li><a href="http://www.tie.ie/" target="_blank"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/logo-4.png" alt="" /></a></li>
        </ul>

	<!-- /block-content -->

	    </div>
	</div>


    </div>
</div>