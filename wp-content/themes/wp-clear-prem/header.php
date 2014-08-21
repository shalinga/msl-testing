<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title(' '); ?> <?php if(wp_title(' ', false)) { echo ' : '; } ?><?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php // Added by shalinga to limit content 
	function new_excerpt_length($length) {
	return 18;
	}
	add_filter('excerpt_length', 'new_excerpt_length');
?> <!-- content limit ends here. -->



<?php error_reporting(E_ALL); ?>
<div class="outer-wrap">

<div id="topnav">
	<div class="limit clearfix">
		<?php if (function_exists('wp_nav_menu')) { ?>
		<?php wp_nav_menu( array( 'theme_location' => 'topnav', 'fallback_cb' => 'nav_fallback' ) ); ?>
		<?php } else { ?>
		<ul>
			<li id="home"<?php if (is_front_page()) { echo " class=\"current_page_item\""; } ?>><a href="<?php bloginfo('url'); ?>"><?php _e("Home", "solostream"); ?></a></li>
			<?php wp_list_pages('title_li='); ?>
		</ul>
		<?php } ?>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>
	</div>
</div>

<div id="wrap">

	<div id="header">
		<div id="head-content" class="clearfix"<?php if ( get_option('solostream_site_title_option') == 'Image/Logo-Type Title' && get_option('solostream_site_logo_url') ) { ?> onclick="location.href='<?php bloginfo('url'); ?>';" style="cursor: pointer;"<?php } ?>>
			<div id="sitetitle">
				<div class="title"><a href="<?php bloginfo('url'); ?>"><img src="/wp-content/uploads/2012/01/logo.png"></a></div> 
			</div>
			<?php include (TEMPLATEPATH . '/banner468head.php'); ?>
		</div>
	</div>

	<?php if ( get_option('solostream_show_catnav') == 'yes'  ) { ?>
	<div id="catnav">
		<div class="limit clearfix">
			<?php if (function_exists('wp_nav_menu')) { ?>
			<?php wp_nav_menu(array( 'theme_location' => 'catnav', 'fallback_cb' => 'catnav_fallback' )); ?>
			<?php } else { ?>
			<ul class="clearfix"><?php wp_list_categories('title_li='); ?></ul>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<?php include (TEMPLATEPATH . '/banner728.php'); ?>
