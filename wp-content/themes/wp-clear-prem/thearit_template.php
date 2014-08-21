<?php
/*
Template Name: www.thearit.com

*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title(' '); ?> <?php if(wp_title(' ', false)) { echo ' : '; } ?><?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/sales-letter.css" type="text/css" media="screen" />

</head>

<body>

	<div id="topnav">
		<div class="limit clearfix">
			<?php if (function_exists('wp_nav_menu')) { ?>
			<?php wp_nav_menu( array( 'theme_location' => 'topnav', 'fallback_cb' => 'nav_fallback') ); ?>
			<?php } else { ?>
			<ul>
				<li id="home"<?php if (is_front_page()) { echo " class=\"current_page_item\""; } ?>><a href="<?php bloginfo('url'); ?>"><?php _e("Home", "solostream"); ?></a></li>
				<?php wp_list_pages('exclude=140&title_li='); ?>			
			</ul>
			<?php } ?>
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</div>
	</div>
	
<div class "thearit">
<iframe title="Mother Sri Lanka: Our Values" width="100%" height="1000px" frameborder="0" src="http://www.thearit.com/accounts/mother-sri-lanka/widgets/mother-sri-lanka-our-values.html"></iframe>
</div>
</body>

</html>