<?php
/*
<div class="art-block clear-block block block-<?php print $block->module ?>" id="block-<?php print $block->module .'-'. $block->delta; ?> <?php print $block_classes; ?> floating">
    <div class="floating">

	<?php if (!empty($block->subject)): ?>
<div class="art-blockheader">
		    <div class="l"></div>
		    <div class="r"></div>
		     <div class="t">
			<h2 class="subject"></h2>
</div>
		</div>

	<?php endif; ?>
<div class="art-blockcontent content">
	    <div class="art-blockcontent-body">
	<!-- block-content -->



	<!-- /block-content -->

	    </div>
	</div>


    </div>
</div>
*/
?>
<ul class="widgets">
    <li class="widget">
		<h3><?php echo $block->subject; ?></h3>
        <?php echo $block->content; ?>
    </li>
</ul>