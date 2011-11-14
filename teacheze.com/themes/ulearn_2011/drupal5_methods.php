<?php

/* Drupal 5 methods definitons */

function ulearn_4_regions() {
  return array(
'right' => t('Right sidebar'),
    'vnavigation_right' => t('Right vertical menu'),
	'content'  => t('Content'),
	'navigation'  => t('Menu'),
	'banner1'  => t('Banner 1'),
	'banner2'  => t('Banner 2'),
	'banner3'  => t('Banner 3'),
	'banner4'  => t('Banner 4'),
	'banner5'  => t('Banner 5'),
	'banner6'  => t('Banner 6'),
	'user1'  => t('User 1'),
	'user2'  => t('User 2'),
	'user3'  => t('User 3'),
	'user4'  => t('User 4'),
	'copyright'  => t('Copyright'),
	'top1' => t('Top 1'),
    'top2' => t('Top 2'),
    'top3' => t('Top 3'),
    'bottom1' => t('Bottom 1'),
    'bottom2' => t('Bottom 2'),
    'bottom3' => t('Bottom 3'));
}

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function _phptemplate_variables($hook, $vars) {
  if ($hook == 'page') {
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

    // Make $front_page variable available
    $vars['front_page'] = url();

    // Hook into color.module
    if (module_exists('color')) {
      _color_page_alter($vars);
    }
 
    drupal_add_js(path_to_theme() .'/script.js', 'theme');
    $vars['scripts'] = drupal_get_js();
    return $vars;
  }
  return array();
}

/**
 * Generate the HTML representing a given menu item ID as a tab.
 *
 * @param $mid
 *   The menu ID to render.
 * @param $active
 *   Whether this tab or a subtab is the active menu item.
 * @param $primary
 *   Whether this tab is a primary tab or a subtab.
 *
 * @ingroup themeable
 */
function ulearn_4_menu_local_task($mid, $active, $primary) {
  $active_class = "";
  if ($active) {
    $active_class .= "active ";
  }
  $link = menu_item_link($mid, FALSE);
  $output = '<span class="'.$active_class.'art-button-wrapper">'.
    '<span class="l"></span>'.
    '<span class="r"></span>'.
    '<a href="?q='.$link['href'].'" class="'.$active_class.'art-button">'.$link['title'].'</a></span>';
  return '<li>'.$output.'</li>';
}

/**
 * Return code that emits an feed icon.
 *
 * @param $url
 *   The url of the feed.
 */
function ulearn_4_feed_icon($url) {
  return '<a href="'. check_url($url) .'" class="art-rss-tag-icon" title="' . t('Syndicate content') . '"></a>';
}

/**
 * Allow themable wrapping of all comments.
 */
function ulearn_4_comment_wrapper($content, $type = null) {
  static $node_type;
  if (isset($type)) $node_type = $type;
  
  ob_start();?>
<div class="art-post">
      <div class="art-post-body">
  <div class="art-post-inner">
  
  <?php $result .= ob_get_clean();

  if ($content && $node_type != 'forum') {
    $result .= '<h2 class="art-postheader comments">' . t('Comments') . '</h2>';
  }
  
  ob_start();?>
<div class="art-postcontent">
      <!-- article-content -->
  
  <?php $result .= ob_get_clean();
  
  $result .= $content;
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
  
  return $result;
}