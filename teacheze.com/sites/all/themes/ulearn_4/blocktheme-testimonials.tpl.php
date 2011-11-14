<?php
$resource = db_query('
    SELECT *
    FROM node
    WHERE `type` = "testimonal"
');
$testimonials = array();
while ($r = db_fetch_object($resource)) {
    $testimonials[] = node_load($r->nid);
}
?>
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

		<div id="slider-testimonials">
            <ul>
                <?php foreach ($testimonials as $t) : ?>
    				<li>
                        <p><?php echo $t->body; ?></p>
                        <div class="auhor"><?php echo $t->title; ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="slider-navigation">
                <?php $i = 1; foreach ($testimonials as $t) : ?>
                    <a href="#" <?php echo ($i == 0) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                <?php $i ++; endforeach; ?>
            </div>
        </div>

	<!-- /block-content -->

	    </div>
	</div>


    </div>
</div>