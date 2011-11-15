<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="<?php print $classes; ?>">
	<div class="rounded_border_float">
    <div class="wrap-corner">
      <div class="t-edge"><div class="l"></div><div class="r"></div></div>
      <div class="l-edge">
        <div class="r-edge">
					<?php if ($block->subject): ?>
						<h3><?php print $block->subject ?></h3>
					<?php endif;?>
				
					<div class="content"><?php print $block->content ?></div>
					<?php print $edit_links; ?>
        </div>
      </div>
      <div class="b-edge"><div class="l"></div><div class="r"></div></div>
    </div>
  </div>
</div>
