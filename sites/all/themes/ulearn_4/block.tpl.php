<div class="art-block clear-block block block-<?php print $block->module ?>" id="block-<?php print $block->module .'-'. $block->delta; ?> <?php print $block_classes; ?>">
    <div class="art-block-body">

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
	
		<?php echo $block->content; ?>

	<!-- /block-content -->
	
	    </div>
	</div>
	

    </div>
</div>
