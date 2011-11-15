<?php

/* Drupal 6 methods definitons */

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {
  $vars['tabs'] = '';    
  $primary = menu_primary_local_tasks();
  if (!empty($primary)) {
    $vars['tabs'] = '<ul class="arttabs_primary">'.$primary.'</ul>';
  }
  
  $vars['tabs2'] = '';
  $secondary = menu_secondary_local_tasks();
  if (!empty($secondary)) {
    $vars['tabs2'] = '<ul class="arttabs_secondary">'.$secondary.'</ul>';
  }
  
  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
}

/**
 * Generate the HTML output for a single local task link.
 *
 * @ingroup themeable
 */
 
function ulearn_4_menu_local_task($link, $active = FALSE) {
  $active_class = "";
  if ($active) {
    $active_class .= "active ";
  }
  $output = preg_replace('~<a href="([^"]*)"[^>]*>([^<]*)</a>~',
    '<span class="'.$active_class.'art-button-wrapper">'.
    '<span class="l"></span>'.
    '<span class="r"></span>'.
    '<a href="$1" class="'.$active_class.'art-button">$2</a></span>', $link);
  return '<li>'.$output.'</li>';
}

/**
 * Return code that emits an feed icon.
 *
 * @param $url
 *   The url of the feed.
 * @param $title
 *   A descriptive title of the feed.
  */
function ulearn_4_feed_icon($url, $title) {
  return '<a href="'. check_url($url) .'" class="art-rss-tag-icon" title="' . $title . '"></a>';
}

/**
 * Add a "Comments" heading above comments except on forum pages.
 */
function ulearn_4_preprocess_comment_wrapper(&$vars) {
  if (!isset($vars['content'])) return;
  
  ob_start();?>
<div class="art-post">
      <div class="art-post-body">
  <div class="art-post-inner">
  
  <?php $result .= ob_get_clean();
   
  if ($vars['node']->type != 'forum') {
    $result .= '<h2 class="art-postheader comments">' . t('Comments') . '</h2>';
  }
  
  ob_start();?>
<div class="art-postcontent">
      <!-- article-content -->
  
  <?php $result .= ob_get_clean();
  
  $result .= $vars['content'];
  ob_start();?>

      <!-- /article-content -->
  </div>
  <div class="cleared"></div>
  
  <?php $result .= ob_get_clean();
  
  ob_start();?>

  </div>
  
      </div>
  </div>
  
  <?php $result .= ob_get_clean();
  
  $vars['content'] = $result;
}