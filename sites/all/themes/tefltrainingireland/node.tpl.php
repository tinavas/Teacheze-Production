<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if ($promote) { print ' promote'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<div class="art-post">
    <div class="art-post-body">
<div class="art-post-inner">
<h2 class="art-postheader"> <?php echo art_node_title_output($title, $node_url, $page); ?>
</h2>
<?php if ($submitted): ?>
<div class="art-postheadericons art-metadata-icons">
<?php echo art_submitted_worker($date, $name); ?>

</div>
<?php endif; ?>
<div class="art-postcontent">
    <!-- article-content -->
<div class="art-article"><?php print $picture; ?><?php echo $content; ?>
<?php if (isset($node->links['node_read_more'])) { echo '<div class="read_more">'.get_html_link_output($node->links['node_read_more']).'</div>'; }?></div>
    <!-- /article-content -->
</div>
<div class="cleared"></div>
<?php if (is_art_links_set($node->links) || !empty($terms)):
$output = art_node_worker($node); 
if (!empty($output)):	?>
<div class="art-postfootericons art-metadata-icons">
<?php echo $output; ?>

</div>
<?php endif; endif; ?>

</div>

    </div>
</div>

</div>