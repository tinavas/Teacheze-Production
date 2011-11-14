<?php
// $Id

require_once("common_methods.php");

if (get_drupal_version() == 5) {
	require_once("drupal5_methods.php");
}
else {
	require_once("drupal6_methods.php");
}

/* Common methods */

function get_drupal_version() {
	$tok = strtok(VERSION, '.');
	//return first part of version number
	return (int)$tok[0];
}

function get_page_language($language) {
	if (get_drupal_version() >= 6) return $language->language;
	return $language;
}

function get_full_path_to_theme() {
	return base_path().path_to_theme();
}

/**
 * Allow themable wrapping of all breadcrumbs.
 */
function ulearn_4_breadcrumb($breadcrumb) {
	if (!empty($breadcrumb)) {
		return '<div class="breadcrumb">'. implode(' | ', $breadcrumb) .'</div>';
	}
}

function ulearn_4_service_links_node_format($links) {
	return '<div class="service-links"><div class="service-label">'. t('Bookmark/Search this post with: ') .'</div>'. art_links_woker($links) .'</div>';
}

/**
 * Theme a form button.
 *
 * @ingroup themeable
 */
function ulearn_4_button($element) {
	// Make sure not to overwrite classes.
	if (isset($element['#attributes']['class'])) {
		$element['#attributes']['class'] = 'form-'.$element['#button_type'].' '.$element['#attributes']['class'].' art-button';
	}
	else {
		$element['#attributes']['class'] = 'form-'.$element['#button_type'].' art-button';
	}

	return '<span class="art-button-wrapper">'.
		'<span class="l"></span>'.
		'<span class="r"></span>'.
		'<input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name']
				 .'" ')  .'id="'. $element['#id'].'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']).'/>'.
	'</span>';
}
/**
 * Sets the body tag class and id attributes.
 *
 * From the Theme Developer's Guide, http://drupal.org/node/32077
 *
 * @param $is_front
 *   boolean Whether or not the current page is the front page.
 * @param $layout
 *   string Which sidebars are being displayed.
 * @return
 *   string The rendered id and class attributes.
 */
function phptemplate_body_attributes($is_front = false, $layout = 'none') {

	if ($is_front) {
		$body_id = $body_class = 'homepage';
	}
	else {
		// Remove base path and any query string.
		global $base_path;
		list(,$path) = explode($base_path, $_SERVER['REQUEST_URI'], 2);
		list($path,) = explode('?', $path, 2);
		$path = rtrim($path, '/');
		// Construct the id name from the path, replacing slashes with dashes.
		$body_id = str_replace('/', '-', $path);
		// Construct the class name from the first part of the path only.
		list($body_class,) = explode('/', $path, 2);
	}
	$body_id = 'page-'. $body_id;
	$body_class = 'section-'. $body_class;

	// Use the same sidebar classes as Garland.
	$sidebar_class = ($layout == 'both') ? 'sidebars' : "sidebar-$layout";

	return " id=\"$body_id\" class=\"$body_class $sidebar_class\"";
}
/**
 * Image assist module support.
 * Added Artisteer styles in IE
*/
function ulearn_4_img_assist_page($content, $attributes = NULL) {
	$title = drupal_get_title();
	$output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
	$output .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">'."\n";
	$output .= "<head>\n";
	$output .= '<title>'. $title ."</title>\n";

	// Note on CSS files from Benjamin Shell:
	// Stylesheets are a problem with image assist. Image assist works great as a
	// TinyMCE plugin, so I want it to LOOK like a TinyMCE plugin. However, it's
	// not always a TinyMCE plugin, so then it should like a themed Drupal page.
	// Advanced users will be able to customize everything, even TinyMCE, so I'm
	// more concerned about everyone else. TinyMCE looks great out-of-the-box so I
	// want image assist to look great as well. My solution to this problem is as
	// follows:
	// If this image assist window was loaded from TinyMCE, then include the
	// TinyMCE popups_css file (configurable with the initialization string on the
	// page that loaded TinyMCE). Otherwise, load drupal.css and the theme's
	// styles. This still leaves out sites that allow users to use the TinyMCE
	// plugin AND the Add Image link (visibility of this link is now a setting).
	// However, on my site I turned off the text link since I use TinyMCE. I think
	// it would confuse users to have an Add Images link AND a button on the
	// TinyMCE toolbar.
	//
	// Note that in both cases the img_assist.css file is loaded last. This
	// provides a way to make style changes to img_assist independently of how it
	// was loaded.
	$output .= drupal_get_html_head();
	$output .= drupal_get_js();
	$output .= "\n<script type=\"text/javascript\"><!-- \n";
	$output .= "  if (parent.tinyMCE && parent.tinyMCEPopup && parent.tinyMCEPopup.getParam('popups_css')) {\n";
	$output .= "    document.write('<link href=\"' + parent.tinyMCEPopup.getParam('popups_css') + '\" rel=\"stylesheet\" type=\"text/css\">');\n";
	$output .= "  } else {\n";
	foreach (drupal_add_css() as $media => $type) {
		$paths = array_merge($type['module'], $type['theme']);
		foreach (array_keys($paths) as $path) {
			// Don't import img_assist.css twice.
			if (!strstr($path, 'img_assist.css')) {
				$output .= "  document.write('<style type=\"text/css\" media=\"{$media}\">@import \"". base_path() . $path ."\";<\/style>');\n";
			}
		}
	}
	$output .= "  }\n";
	$output .= "--></script>\n";
	// Ensure that img_assist.js is imported last.
	$path = drupal_get_path('module', 'img_assist') .'/img_assist_popup.css';
	$output .= "<style type=\"text/css\" media=\"all\">@import \"". base_path() . $path ."\";</style>\n";

	$output .= '<link rel="stylesheet" href="'.get_full_path_to_theme().'/style.css" type="text/css" />'."\n";
	$output .= '<!--[if IE 6]><link rel="stylesheet" href="'.get_full_path_to_theme().'/style.ie6.css" type="text/css" /><![endif]-->'."\n";
	$output .= '<!--[if IE 7]><link rel="stylesheet" href="'.get_full_path_to_theme().'/style.ie7.css" type="text/css" /><![endif]-->'."\n";

	$output .= "</head>\n";
	$output .= '<body'. drupal_attributes($attributes) .">\n";

	$output .= theme_status_messages();

	$output .= "\n";
	$output .= $content;
	$output .= "\n";
	$output .= '</body>';
	$output .= '</html>';
	return $output;
}

function verbose_time_distance($from_time, $to_time = -1, $showLessThanAMinute = false) {
	if ($to_time == -1) {
		$to_time = time();
	}
    $distanceInSeconds = round(abs($to_time - $from_time));

    if(function_exists('lang')) {
        $lang = 'lang';
    } else {
        //Empty function if we don't have translation function
        $lang = create_function('$arg', 'return $arg; ');
    }

    $distanceInMinutes = round($distanceInSeconds / 60);
    if ( $distanceInMinutes <= 1 ) {
        if ( !$showLessThanAMinute ) {
            return ($distanceInMinutes == 0) ? $lang('less than a minute') : $lang('1 minute');
        } else {
            if ( $distanceInSeconds < 5 ) {
                return $lang('less than 5 seconds');
            }
            if ( $distanceInSeconds < 10 ) {
                return $lang('less than 10 seconds');
            }
            if ( $distanceInSeconds < 20 ) {
                return $lang('less than 20 seconds');
            }
            if ( $distanceInSeconds < 40 ) {
                return $lang('about half a minute');
            }
            if ( $distanceInSeconds < 60 ) {
                return $lang('less than a minute');
            }

            return $lang('1 minute');
        }
    }
    if ( $distanceInMinutes < 45 ) {
        return $distanceInMinutes . ' ' . $lang('minutes');
    }
    if ( $distanceInMinutes < 90 ) {
        return $lang('about 1 hour');
    }
    if ( $distanceInMinutes < 1440 ) {
        return $lang('about') . ' ' . round(floatval($distanceInMinutes) / 60.0) . ' ' . $lang('hours');
    }
    if ( $distanceInMinutes < 2880 ) {
        return '1 ' .  $lang('day');
    }
    if ( $distanceInMinutes < 43200 ) {
        return $lang('about'). ' ' . round(floatval($distanceInMinutes) / 1440) . ' ' . $lang('days');
    }
    if ( $distanceInMinutes < 86400 ) {
        return $lang('about') .' 1 ' . $lang('month');
    }
    if ( $distanceInMinutes < 525600 ) {
        return round(floatval($distanceInMinutes) / 43200) . ' ' . $lang('months');
    }
    if ( $distanceInMinutes < 1051199 ) {
        return $lang('about') . ' 1 ' . $lang('year');
    }

    return $lang('over') . ' ' . round(floatval($distanceInMinutes) / 525600) . ' ' . $lang('years');
}

function ul_get_tweets() {
	$url = 'http://twitter.com/statuses/user_timeline/26570794.rss';

	$cache = dirname(__FILE__) . '/cache/twitter.cache';
	if (file_exists($cache) && filemtime($cache) > time() - 600) {
		$tweets = json_decode(file_get_contents($cache));
	} else {
		$xml = file_get_contents($url);
		$dom = new DOMDocument();
		$dom->loadXML($xml);
		$items = $dom->getElementsByTagName('item');
		$tweets = array();
		for ($i=0; $i < min(2, $items->length); $i++) {
			$item = $items->item($i);
			$tweets[] = (object) array(
				'title'=>html_entity_decode($item->getElementsByTagName('title')->item(0)->nodeValue),
				'time'=>verbose_time_distance(strtotime($item->getElementsByTagName('pubDate')->item(0)->nodeValue)),
			);
		}

		$handle = fopen($cache, 'w');
		fwrite($handle, json_encode($tweets));
		fclose($handle);
	}
	return $tweets;
}

define('STR_WORD_COUNT_FORMAT_ADD_POSITIONS', 2);
function ul_shortalize($input, $words_limit=15, $strip_tags = true, $end = '...') {
	if ($strip_tags) {
		$input = strip_tags($input);
	}
    $words_limit = abs(intval($words_limit));
    if ($words_limit==0) {
        return $input;
    }
    $words = str_word_count($input, STR_WORD_COUNT_FORMAT_ADD_POSITIONS, '0123456789');
    if (count($words)<=$words_limit + 1) {
        return $input;
    }
    $loop_counter = 0;
    foreach ($words as $word_position => $word) {
        $loop_counter++;
        if ($loop_counter==$words_limit + 1) {
            return substr($input, 0, $word_position) . $end;
        }
    }
}