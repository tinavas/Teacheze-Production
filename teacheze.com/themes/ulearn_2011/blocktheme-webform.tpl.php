<div class="art-block clear-block block block-<?php print $block->module ?>" id="block-<?php print $block->module .'-'. $block->delta; ?> <?php print $block_classes; ?>">
    <div class="webform">

	<?php if (!empty($block->subject)): ?>
<div class="blockheader-webform">
		    <div class="l-w"></div>
		    <div class="r-w"></div>
		     <div class="t-w">	
			<h2 class="subject-w"><?php echo $block->subject; ?></h2>
</div>
		</div>
		    
	<?php endif; ?>
<div class="webform-content">
	    <div class="webform-body">
	<!-- block-content -->
	
		<?php echo $block->content; ?>

	<!-- /block-content -->
	
	    </div>
	</div>
	

    </div>
</div>
