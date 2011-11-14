<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo get_page_language($language); ?>" xml:lang="<?php echo get_page_language($language); ?>">
<head>
    <?php echo $head; ?>
    <title><?php if (isset($head_title )) { echo $head_title; } ?></title>

    <?php
    $styles = preg_replace('~<link type="text/css" rel="stylesheet" media="all" href="/sites/all/themes/ulearn_4/[^"]*" />~', '', $styles);
    $styles .= '<link type="text/css" rel="stylesheet" media="all" href="/sites/all/themes/ulearn_4/css/rounded_borders.css?I';
    ?>
    <?php echo $styles ?>

    <script src="http://www.ulearn.ie/misc/jquery.js?n" type="text/javascript" charset="utf-8"></script>

    <?php echo $scripts ?>

    <link rel="stylesheet" href="<?php echo $base_path . $directory; ?>/reskin/css/style.css" type="text/css" media="all" />
    <!--[if IE 6]>
        <link rel="stylesheet" href="<?php echo $base_path . $directory; ?>/reskin/css/ie6.css" type="text/css" media="all" />
        <script src="<?php echo $base_path . $directory; ?>/reskin/js/png-fix.js" type="text/javascript" charset="utf-8"></script>
    <![endif]-->
    <link rel="shortcut icon" href="<?php echo $base_path . $directory; ?>/reskin/css/images/favicon.ico" />
    <script src="<?php echo $base_path . $directory; ?>/reskin/js/jquery.jcarousel.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
    <script src="<?php echo $base_path . $directory; ?>/reskin/js/func.js" type="text/javascript" charset="utf-8"></script>
</head>
<body<?php print phptemplate_body_attributes($is_front, $layout); ?>>

<div id="wrapper">
    <div id="header">
        <div class="shell">
            <h1 id="logo"><a href="<?php echo $base_path; ?>">ULEARN : English school dublin</a></h1>
            <div class="cl">&nbsp;</div>
            <div id="navigation">
                <?php echo $navigation; ?>
            </div>
            <div class="cl">&nbsp;</div>
            <div id="header-float-position" style="display: none;"><?php print $headerfloat ?></div>
            <ul id="lang">
                <li><a href="#"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/flag-uk.png" alt="" /></a></li>
                <li><a href="#es"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/flag-1.png" alt="" /></a></li>
                <li><a href="#pt-br"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/flag-2.png" alt="" /></a></li>
                <li><a href="#it"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/flag-3.png" alt="" /></a></li>
                <li><a href="#ko"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/korea.png" alt="" /></a></li>
            </ul>
            <script type="text/javascript" charset="utf-8">
            (function($){
                $('#lang a').click(function() {
                    var lang = $(this).attr('href').substr(1);
                    var url = window.location.pathname;
                    // url = url.replace(/^\/$|^\/(en|es|pt-br|it|ko)\/?$|^\/(en|es|pt-br|it|ko)\/(.*)/, '/' + lang + '/$3');
                    url = '/' + lang;
                    window.location = url;
                    return false;
                });
            })(jQuery)
            </script>
            <div class="cl">&nbsp;</div>
            <?php if (drupal_is_front_page() || $node->path == 'home') : ?>
                <div id="featured">
                    <div class="image">
                        <img src="<?php echo $base_path . $directory; ?>/reskin/css/images/featured-image1.png" alt="" />
                    </div>
                    <h2 class="txt-learn-today">Want to Learn English Today?</h2>
                    <div class="bar">
                        <a href="skype:U-Learn?call" class="skype-us">Skype <br />Us!</a>
                        <a href="/booking" class="get-quotation">Get A<br /> Quotation</a>
                    </div>
                    <a href="tel:+35318787339" class="btn-call">Call Us +353 (0)1 8787 339</a>
                </div>
            <?php endif; ?>
        </div>
    </div><!-- /header -->

    <div id="main">
        <div class="shell">
            <?php if (!empty($banner1)) { echo '<div id="banner1">'.$banner1.'</div>'; } ?>
            <?php if (!empty($banner2)) { echo '<div id="banner2">'.$banner2.'</div>'; } ?>
            <?php if (!empty($banner3)) { echo '<div id="banner3">'.$banner3.'</div>'; } ?>

            <div id="content" style="<?php echo ($_GET['q'] == 'booking' || strstr($_GET['q'], 'civicrm')) ? 'display: block; float: none; width: 100%;' : ''; ?>">
                <?php if (!drupal_is_front_page() && $node->path != 'home') : ?>
                    <div class="post">
                <?php endif; ?>

                <?php if (!empty($help)) { echo $help; } ?>
                <?php if (!empty($messages)) { echo $messages; } ?>
                <?php if ($_GET['q'] == 'booking') : ?>
                    <div class="art-content-layout-row" style="display: inline; float: right; width: 220px;">
                        <?php
                        $art_sidebar_right = (isset($right) && !empty($right)) ? $right : $sidebar_right;
                        if (!empty($art_sidebar_right) || !empty($vnavigation_right)) echo '<div class="'.art_get_sidebar_style($art_sidebar_right, $vnavigation_right, 'art-sidebar1').'">' . $vnavigation_right . $art_sidebar_right . "</div>";
                        ?>
                    </div>
                <?php endif; ?>
                <?php echo $content; ?>

                <?php if (!drupal_is_front_page() && $node->path != 'home') : ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($_GET['q'] != 'booking' && !strstr($_GET['q'], 'civicrm')) : ?>
                <div id="sidebar">
                    <ul class="social-links" style="padding-left: 15px;">
                        <li><a href="http://www.ulearn.ie/blog"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/icon-rss.png" alt="" /></a></li>
                        <?php /* ?><li><a target="_blank" href="http://ulearn.ie/twitter.com/ULearnDublin"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/icon-twitter.png" alt="" /></a></li><?php */ ?>
                        <li><a target="_blank" href="http://www.facebook.com/pages/U-Learn-Dublin-English-School/110245482320090"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/icon-facebook.png" alt="" /></a></li>
                        <li><a href="skype:U-Learn?call"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/icon-skype2.png" alt="" /></a></li>
                        <li><a target="_blank" href="http://www.youtube.com/user/ULearnDublin"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/icon-youtube.png" alt="" /></a></li>
                        <li><a target="_blank" href="http://maps.google.ie/maps/place?rlz=1C1SVEE_enIE419&um=1&ie=UTF-8&q=ulearn+english+school+dublin&fb=1&gl=ie&hq=ulearn+english+school&hnear=Dublin,+Co.+Fingal&cid=7136853069030798392"><img src="<?php echo $base_path . $directory; ?>/reskin/css/images/icon-google.png" alt="" /></a></li>
                        <li style="padding-top: 4px;"><g:plusone count="true"></g:plusone></li>
                    </ul>
                    <?php echo $sidebar; ?>
                </div>
            <?php endif; ?>
            <div class="cl">&nbsp;</div>

            <?php if (!empty($banner4)) { echo '<div id="banner4">'.$banner4.'</div>'; } ?>
            <?php if (!empty($banner5)) { echo '<div id="banner5">'.$banner5.'</div>'; } ?>
            <?php if (!empty($banner6)) { echo '<div id="banner6">'.$banner6.'</div>'; } ?>
        </div>
    </div>

    <div id="footer">
        <div id="footer-inner">
            <div class="shell">
                <div class="col-1">
                    <h4>Location</h4>
                    <iframe width="200" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.ie/maps?hl=en&amp;ie=UTF8&amp;q=ulearn+dublin&amp;fb=1&amp;gl=ie&amp;hq=ulearn&amp;hnear=0x48670e80ea27ac2f:0xa00c7a9973171a0,Dublin,+Co.+Fingal&amp;cid=0,0,7136853069030798392&amp;ll=53.337626,-6.262035&amp;spn=0.002562,0.00427&amp;z=16&amp;iwloc=A&amp;output=embed"></iframe>
                    <?php /* ?>
                    <?php $tweets = ul_get_tweets(); ?>
                    <h4>Recent Tweets</h4>
                    <div class="latest-tweets">
                        <ul>
                            <?php $i = 0; foreach ($tweets as $t) : ?>
                                <li class="<?php echo ($i == count($tweets) - 1) ? 'last' : ''; ?>">
                                    <?php echo $t->title; ?>
                                    <div class="meta">
                                        about <?php echo $t->time; ?> ago
                                    </div>
                                </li>
                            <?php $i ++; endforeach; ?>
                        </ul>
                    </div>
                    <?php */ ?>
                </div>
                <div class="col-2">
                    <?php
                    $connection = mysql_connect('localhost', 'dialogmi_neil', 'Pa22w0rd');
                    mysql_select_db('dialogmi_wpulearn', $connection);
                    $resource = mysql_query('
                        SELECT *
                        FROM wp_posts
                        WHERE post_type = "post"
                            AND post_status = "publish"
                        ORDER BY post_date DESC
                        LIMIT 1
                    ') or die(mysql_error());
                    $post = mysql_fetch_assoc($resource);
                    ?>
                    <h4>From The Blog</h4>
                    <div class="featured-post">
                        <h5><a href="/blog/?p=<?php echo $post['ID']; ?>"><?php echo $post['post_title']; ?></a></h5>
                        <p>
                            <?php echo trim(ul_shortalize($post['post_content'], 40)); ?> <a href="/blog/?p=<?php echo $post['ID']; ?>">read more</a>
                        </p>
                    </div>
                </div>
                <div class="col-3">
                    <h4>Find Out More</h4>
                    <div class="contacts">
                        ULearn, 97 St Stephens Green, <br />
                        Dublin 2, Ireland<br /><br />
                        <strong>Tel:</strong> 00353 1 878 7339<br />
                        <strong>Fax:</strong> 00353 1 878 7334<br />
                        <strong>Email:</strong> info@ulearn.ie
                    </div>
                </div>
                <div class="cl">&nbsp;</div>
                <p id="footer-links" style="padding: 10px 0px; text-align: center;">Web Design by <a href="http://sheenaoosten.com/" target="_blank">Sheena Oosten</a> | SEO Dublin by <a href="http://www.localsearchmarketing.ie/" target="_blank">Local Search Marketing</a></p>
                <div class="cl">&nbsp;</div>
                <script type="text/javascript" charset="utf-8">
                (function($){
                    $('#footer-links a').click(function (){ // overriding an unknown js file which redirects the user regardless of the target attribute on click
                        window.open($(this).attr('href'));
                        return false;
                    });
                })(jQuery)
                </script>
            </div>
        </div>
    </div><!-- /footer -->
</div><!-- /wrapper -->

<?php
/*
?>

<div id="art-main">
<div class="art-sheet">
    <div class="art-sheet-tl"></div>
    <div class="art-sheet-tr"></div>
    <div class="art-sheet-bl"></div>
    <div class="art-sheet-br"></div>
    <div class="art-sheet-tc"></div>
    <div class="art-sheet-bc"></div>
    <div class="art-sheet-cl"></div>
    <div class="art-sheet-cr"></div>
    <div class="art-sheet-cc"></div>
    <div class="art-sheet-body">
	<div class="headerfloat">
	<?php print $headerfloat ?></div>

<div class="art-header">
    <div class="art-header-jpeg"></div>
<div class="art-logo">
     <?php   if (!empty($site_name)) { echo '<h1 class="art-logo-name"><a href="'.check_url($front_page).'" title = "'.$site_name.'">'.$site_name.'</a></h1>'; } ?>
     <?php   if (!empty($site_slogan)) { echo '<div class="art-logo-text">'.$site_slogan.'</div>'; } ?>
</div>

</div>
<?php if (!empty($navigation)): ?>

<div class="art-nav">
    <div class="l"></div>
    <div class="r"></div>
    <?php echo $navigation; ?>
</div>

<?php endif;?>
<?php if (!empty($banner1)) { echo '<div id="banner1">'.$banner1.'</div>'; } ?>
<?php echo art_placeholders_output($top1, $top2, $top3); ?>
<div class="art-content-layout">
    <div class="art-content-layout-row">

<?php $art_sidebar_right = (isset($right) && !empty($right)) ? $right : $sidebar_right;
echo '<div class="'.art_get_sidebar_style($art_sidebar_right, $vnavigation_right, 'art-content').'">'; ?>

<?php if (!empty($banner2)) { echo '<div id="banner2">'.$banner2.'</div>'; } ?>
<?php if ((!empty($user1)) && (!empty($user2))) : ?>

<table class="position" cellpadding="0" cellspacing="0" border="0">
<tr valign="top"><td class="half-width"><?php echo $user1; ?></td>
<td><?php echo $user2; ?></td></tr>
</table>
<?php else: ?>
<?php if (!empty($user1)) { echo '<div id="user1">'.$user1.'</div>'; }?>
<?php if (!empty($user2)) { echo '<div id="user2">'.$user2.'</div>'; }?>
<?php endif; ?>
<?php if (!empty($banner3)) { echo '<div id="banner3">'.$banner3.'</div>'; } ?>
<?php if (($is_front) || (isset($node) && isset($node->nid))): ?>
<?php if (!empty($tabs) || !empty($tabs2)): ?>
<div class="art-post">
    <div class="art-post-body">

<div class="art-post-inner">
<div class="art-postcontent">

<?php if (!empty($tabs)) { echo $tabs.'<div class="cleared"></div>'; }; ?>
<?php if (!empty($tabs2)) { echo $tabs2.'<div class="cleared"></div>'; } ?>

</div>
<div class="cleared"></div>
</div>
    </div>
</div>

<?php endif; ?>
<?php if (!empty($mission)) { echo '<div id="mission">'.$mission.'</div>'; }; ?>
<?php if (!empty($help)) { echo $help; } ?>
<?php if (!empty($messages)) { echo $messages; } ?>
<?php $art_post_position = strpos($content, "art-post"); ?>
<?php if ($art_post_position === FALSE): ?>
<div class="art-post">

    <div class="art-post-body">
<div class="art-post-inner">
<div class="art-postcontent">

<?php endif; ?>
<?php echo art_content_replace($content); ?>
<?php if ($art_post_position === FALSE): ?>

</div>
<div class="cleared"></div>

</div>

    </div>
</div>
<?php endif; ?>
<?php else: ?>
<div class="art-post">
    <div class="art-post-body">
<div class="art-post-inner">
<div class="art-postcontent">

<?php if (!empty($title)): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h2>'; endif; ?>
<?php if (!empty($tabs)) { echo $tabs.'<div class="cleared"></div>'; }; ?>
<?php if (!empty($tabs2)) { echo $tabs2.'<div class="cleared"></div>'; } ?>
<?php if (!empty($mission)) { echo '<div id="mission">'.$mission.'</div>'; }; ?>
<?php if (!empty($help)) { echo $help; } ?>
<?php if (!empty($messages)) { echo $messages; } ?>
<?php echo art_content_replace($content); ?>

</div>
<div class="cleared"></div>

</div>

    </div>
</div>

<?php endif; ?>
<?php if (!empty($banner4)) { echo '<div id="banner4">'.$banner4.'</div>'; } ?>
<?php if (!empty($user3) && !empty($user4)) : ?>
<table class="position" cellpadding="0" cellspacing="0" border="0">
<tr valign="top"><td class="half-width"><?php echo $user3; ?></td>
<td><?php echo $user4; ?></td></tr>
</table>
<?php else: ?>
<?php if (!empty($user3)) { echo '<div id="user1">'.$user3.'</div>'; }?>
<?php if (!empty($user4)) { echo '<div id="user2">'.$user4.'</div>'; }?>
<?php endif; ?>
<?php if (!empty($banner5)) { echo '<div id="banner5">'.$banner5.'</div>'; } ?>
</div>
<?php $art_sidebar_right = (isset($right) && !empty($right)) ? $right : $sidebar_right;
if (!empty($art_sidebar_right) || !empty($vnavigation_right)) echo '<div class="'.art_get_sidebar_style($art_sidebar_right, $vnavigation_right, 'art-sidebar1').'">' . $vnavigation_right . $art_sidebar_right . "</div>"; ?>

    </div>
</div>
<div class="cleared"></div>
<?php echo art_placeholders_output($bottom1, $bottom2, $bottom3); ?>
<?php if (!empty($banner6)) { echo '<div id="banner6">'.$banner6.'</div>'; } ?>
<div class="art-footer">
    <div class="art-footer-t"></div>
    <div class="art-footer-l"></div>
    <div class="art-footer-b"></div>
    <div class="art-footer-r"></div>
    <div class="art-footer-body">
        <?php
            if (!empty($feed_icons)) {
                echo $feed_icons;
            }
            else {
                echo '<a href="'.url("rss.xml").'" class="art-rss-tag-icon"></a>';
            }
        ?>
        <div class="art-footer-text">
        <?php
            if (!empty($footer_message) && (trim($footer_message) != '')) {
                echo $footer_message;
            }
            else {
                // Footer copyright removed: <p><a href="#">Contact Us</a>&nbsp;|&nbsp;<a href="#">Terms of Use</a>&nbsp;|&nbsp;<a href="#">Trademarks</a>&nbsp;|&nbsp;<a href="#">Privacy Statement</a><br />'.'Copyright &copy; 2010&nbsp;'.$site_name.'.&nbsp;All Rights Reserved.</p>
                echo '';
            }
        ?>
        <?php if (!empty($copyright)) { echo $copyright; } ?>
        </div>
		<div class="cleared"></div>
    </div>
</div>

    </div>
</div>
<div class="cleared"></div>

</div>
<?php
*/
?>

<?php print $closure; ?>

</body>
</html>