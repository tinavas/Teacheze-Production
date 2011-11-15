<div class="art-block clear-block block block-<?php print $block->module ?>" id="block-<?php print $block->module .'-'. $block->delta; ?>">
    <div class="art-block-tl"></div>
    <div class="art-block-tr"></div>
    <div class="art-block-bl"></div>
    <div class="art-block-br"></div>
    <div class="art-block-tc"></div>
    <div class="art-block-bc"></div>
    <div class="art-block-cl"></div>
    <div class="art-block-cr"></div>
    <div class="art-block-cc"></div>
    <div class="art-block-body">

	<?php if (!empty($block->subject)): ?>
<div class="art-blockheader">
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
