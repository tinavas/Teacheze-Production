<?php

/* Common Drupal methods definitons using in Artisteer theme export */

/**
 * Generate the HTML representing a given menu with Artisteer style.
 *
*/
function art_menu_worker($content = NULL, $show_sub_menus, $menu_class) {
  if (!$content) {
    return '';
  }

  $output = $content;
  // used to support Menutrails module
  $output = str_replace("active-trail", "active-trail active", $output);

  $menu_str = ' class="menu"';
  if(strpos($output, $menu_str) !== FALSE) {
    $empty_str = '';
    $pattern = '/class="menu"/i';
    $replacement = 'class="'. $menu_class .'"';
    $output = preg_replace($pattern, $replacement, $output, 1);
    $output = str_replace($menu_str, $empty_str, $output);
  }

  if (class_exists('DOMDocument')) {
    $output = art_menu_xml_parcer($output, $show_sub_menus, $menu_class);
    /* Support Block Edit Link module */
	  $output = str_replace('<!DOCTYPE root>', $empty_str, $output);
  }
  else {
    $output = preg_replace('~(<a [^>]*>)([^<]*)(</a>)~', '$1<span class="l"></span><span class="r"></span><span class="t">$2</span>$3', $output);
  }
  
  return $output;
}

function art_menu_xml_parcer($content, $show_sub_menus, $menu_class) {
  $parent_id = $menu_class . '-id';
  /* Support Block Edit Link module */
  $doc_content = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE root [
<!ENTITY nbsp "&#160;">
]>
  <div id="$parent_id">$content</div>
XML;

  $doc = new DOMDocument();
  $doc->loadXML($doc_content);
  $parent = $doc->documentElement;
  $elements = $parent->childNodes;

  $ul_children = NULL;
  foreach($elements as $element) {
    if ($element->tagName != "ul") continue;
    $ul_children = $element->childNodes;
    break;
  }

  if ($ul_children == NULL) return $content;
  $ul_children = art_menu_style_parcer($doc, $ul_children, $show_sub_menus);

  return $doc->saveHTML();
}

function art_menu_style_parcer($doc, $elements, $show_sub_menus) {
  $parent_to_delete = array();
  $childNodes_to_delete = array();
  foreach ($elements as $element) {
    if (is_a($element, "DOMElement") && ($element->tagName == "li")) {
      $children = $element->childNodes;
      $parent_class = $element->getAttribute("class");
      $is_parent_class_active = strpos($parent_class, "active") !== FALSE;

      foreach ($children as $child) {
        if (is_a($child, "DOMElement") && ($child->tagName == "a")) {
          $caption = $child->nodeValue;
          if (empty($caption) || $caption=='test') {
            $childNodes_to_delete[] = $child;
            $parentNodes_to_delete[] = $element;
            break;
          }

          $child->nodeValue = "";
          if ($is_parent_class_active) {
            $child->setAttribute("class", $child->getAttribute("class").' active');
          }

          $spanL = $doc->createElement("span");
          $spanL->setAttribute("class", "l");
          $spanL->nodeValue = " ";
          $child->appendChild($spanL);

          $spanR = $doc->createElement("span");
          $spanR->setAttribute("class", "r");
          $spanR->nodeValue = " ";
          $child->appendChild($spanR);

          $spanT = $doc->createElement("span");
          $spanT->setAttribute("class", "t");
          $spanT->nodeValue = check_plain($caption);
          $child->appendChild($spanT);
        }
        else if (!$show_sub_menus) {
          $childNodes_to_delete[] = $child;
        }
      }
    }
  }

  art_remove_elements($childNodes_to_delete);
  art_remove_elements($parentNodes_to_delete);
  return $elements;
}

function art_remove_elements($elements_to_delete) {
  if (!isset($elements_to_delete)) return;
  foreach($elements_to_delete as $element) {
    if ($element != null) {
      $element->parentNode->removeChild($element);
    }
  }
}

function art_node_worker($node) {
  $links_output = art_links_woker($node->links);
  $terms_output = art_terms_worker($node->taxonomy);

  $output = $links_output;
  if (!empty($links_output) && !empty($terms_output)) {
    $output .= '&nbsp;|&nbsp;';
  }
  $output .= $terms_output;
  return $output;
}

/*
 * Split out taxonomy terms by vocabulary.
 *
 * @param $terms
 *   An object providing all relevant information for displaying terms:
 *
 * @ingroup themeable
 */
function art_terms_worker($terms) {
  $output = '';
  if (!empty($terms)) {
$links = array();
    ob_start();?>
     <?php
    $output .= ob_get_clean();
    $output .= t('Tags: ');
    foreach ($terms as $term) {
      $links[] = l($term->name, taxonomy_term_path($term), array('rel' => 'tag', 'title' => strip_tags($term->description)));
    }  
    $output .= implode(', ', $links);
    
  }
  return $output;
}

/**
 * Return a themed set of links.
 *
 * @param $links
 *   A keyed array of links to be themed.
 * @param $attributes
 *   A keyed array of attributes
 * @return
 *   A string containing an unordered list of links.
 */
function art_links_woker($links, $attributes = array('class' => 'links')) {
  $output = '';

  if (!empty($links)) {
    $output = '';

    $num_links = count($links);
    $index = 0;

    foreach ($links as $key => $link) {
      $class = $key;
      if (strpos ($class, "read_more") !== FALSE) {
        continue;
      }

      // Automatically add a class to each link and also to each LI
      if (isset($link['attributes']) && isset($link['attributes']['class'])) {
        $link['attributes']['class'] .= ' ' . $key;
      }
      else {
        $link['attributes']['class'] = $key;
      }

      // Add first and last classes to the list of links to help out themers.
      $extra_class = '';
      if ($index == 1) {
        $extra_class .= 'first ';
      }
      if ($index == $num_links) {
        $extra_class .= 'last ';
      }

      if (!empty($class)) {
if (strpos ($class, "comment") !== FALSE) {
          if ($index > 0) {
          $output .= '&nbsp;|&nbsp;';
        }
        ob_start();?>
         <?php
        $output .= ob_get_clean();
        $output .= get_html_link_output($link);
        $index++;
        continue;
        }
        
if ($index > 0) {
          $output .= '&nbsp|&nbsp';
        }
        ob_start();?>
         <?php
        $output .= ob_get_clean();
        $output .= get_html_link_output($link);
        $index++;
        
      }
      else {
        $output .= '&nbsp;|&nbsp;' . get_html_link_output($link);
        $index++;
      }
    }
  }

  return $output;
}

function get_html_link_output($link) {
  $output = '';
  // Is the title HTML?
  $html = isset($link['html']) ? $link['html'] : NULL;

  // Initialize fragment and query variables.
  $link['query'] = isset($link['query']) ? $link['query'] : NULL;
  $link['fragment'] = isset($link['fragment']) ? $link['fragment'] : NULL;

  if (isset($link['href'])) {
    if (get_drupal_version() == 5) {
      $output = l($link['title'], $link['href'], $link['attributes'], $link['query'], $link['fragment'], FALSE, $html);
    }
    else {
      $output = l($link['title'], $link['href'], array('language' => $link['language'], 'attributes'=>$link['attributes'], 'query'=>$link['query'], 'fragment'=>$link['fragment'], 'absolute'=>FALSE, 'html'=>$html));
    }
  }
  else if ($link['title']) {
    if (!$html) {
      $link['title'] = check_plain($link['title']);
    }
    $output = $link['title'];
  }

  return $output;
}

function art_content_replace($content) {
  $first_time_str = '<div id="first-time"';
  $article_str = ' class="art-article"';
  $pos = strpos($content, $first_time_str);
  if($pos !== FALSE)
  {
    $output = str_replace($first_time_str, $first_time_str . $article_str, $content);
    $output = <<< EOT
<div class="art-post">
        <div class="art-post-body">
    <div class="art-post-inner">
    
<div class="art-postcontent">
        <!-- article-content -->
    
      $output

        <!-- /article-content -->
    </div>
    <div class="cleared"></div>
    

    </div>
    
        </div>
    </div>
    
EOT;
  }
  else 
  {
    $output = $content;
  }
  return $output;
}

function art_placeholders_output($var1, $var2, $var3) {
  $output = '';
  if (!empty($var1) && !empty($var2) && !empty($var3)) {
    $output .= <<< EOT
      <table class="position" cellpadding="0" cellspacing="0" border="0">
        <tr valign="top">
          <td class="third-width">$var1</td>
          <td class="third-width">$var2</td>
          <td>$var3</td>
        </tr>
      </table>
EOT;
  }
  else if (!empty($var1) && !empty($var2)) {
    $output .= <<< EOT
      <table class="position" cellpadding="0" cellspacing="0" border="0">
        <tr valign="top">
          <td class="third-width">$var1</td>
          <td>$var2</td>
        </tr>
      </table>
EOT;
  }
  else if (!empty($var2) && !empty($var3)) {
    $output .= <<< EOT
      <table class="position" cellpadding="0" cellspacing="0" border="0">
        <tr valign="top">
          <td class="two-thirds-width">$var2</td>
          <td>$var3</td>
        </tr>
      </table>
EOT;
  }
  else if (!empty($var1) && !empty($var3)) {
    $output .= <<< EOT
      <table class="position" cellpadding="0" cellspacing="0" border="0">
        <tr valign="top">
          <td class="half-width">$var1</td>
          <td>$var3</td>
        </tr>
      </table>
EOT;
  }
  else {
    if (!empty($var1)) {
      $output .= <<< EOT
        <div id="var1">$var1</div>
EOT;
    }
    if (!empty($var2)) {
      $output .= <<< EOT
        <div id="var1">$var2</div>
EOT;
    }
    if (!empty($var3)) {
      $output .= <<< EOT
        <div id="var1">$var3</div>
EOT;
    }
  }

  return $output;
}

function art_sidebar($s, $sidebar) {
  if (!empty($s)) return $s;
  if (!empty($sidebar)) return $sidebar;
  return NULL;
}

function art_get_sidebar_style($sidebar, $vnavigation, $class) {
  $output = 'art-layout-cell ';
  if (empty($sidebar) && empty($vnavigation)) {
    $output .= 'art-content';
  }
  else {
    $output .= $class;
  }
  return $output;
}

function art_get_content_cell_style($left, $vnav_left, $right, $vnav_right, $content) {
  if (empty($left) && empty($vnav_left) && empty($right) && empty($vnav_right))
    return 'art-layout-cell art-content-wide';
  if (empty($left) && empty($vnav_left))
    return 'art-layout-cell art-content-sidebar1';
  if (empty($right) && empty($vnav_right))
    return 'art-layout-cell art-content-sidebar2';
  return 'art-layout-cell art-content';
}

function art_submitted_worker($date, $author) {
  $output = '';
  if ($date != '') {
ob_start();?>
     <?php
    $output .= ob_get_clean();
    $output .= $date;
    
  }
  if ($author != '') {
ob_start();?>
     <?php if ($output != '') {
      $output .= '&nbsp;|&nbsp;';
    }
    $output .= ob_get_clean();
    $output .= $author;
    
  }
  return $output;
}

function is_art_links_set($links) {
  $size = sizeof($links);
  if ($size == 0) {
    return FALSE;
  }

  //check if there's "Read more" in node links only  
  $read_more_link = $links['node_read_more'];
  if ($read_more_link != NULL && $size == 1) {
    return FALSE;
  }

  return TRUE;
}

/**
 * Method to define node title output.
 *
*/
function art_node_title_output($title, $node_url, $page) {
  $output = '';
  if ($page == 0)
    $output = '<a href="' . $node_url . '" title="' . $title . '">' . $title . '</a>';
  else
    $output = $title;
  return $output;
}

function art_vmenu_output($subject, $content) {
  if (empty($content))
    return;

  $bvm = "<div class=\"art-vmenublock\">\r\n    <div class=\"art-vmenublock-body\">\r\n";
  $bvmt = "<div class=\"art-vmenublockheader\">\r\n    <div class=\"l\"></div>\r\n    <div class=\"r\"></div>\r\n     <div class=\"t\">";
  $evmt = "</div>\r\n</div>\r\n";
  $bvmc = "<div class=\"art-vmenublockcontent\">\r\n    <div class=\"art-vmenublockcontent-body\">\r\n<!-- block-content -->\r\n";
  $evmc = "\r\n<!-- /block-content -->\r\n\r\n    </div>\r\n</div>\r\n";
  $evm = "\r\n    </div>\r\n</div>\r\n";
  echo $bvm;
  if ('' != $bvmt && '' != $evmt && !empty($subject)) {
    echo $bvmt;
    echo $subject;
    echo $evmt;
  }
  echo $bvmc;
  echo art_menu_worker($content, true, 'art-vmenu');
  echo $evmc;
  echo $evm;
}
