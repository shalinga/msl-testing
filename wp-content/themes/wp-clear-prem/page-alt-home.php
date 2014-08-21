<?php
/*
Template Name: Alternate Home
*/
?>

<?php get_header(); ?>

	<?php
	global $wp_query;
	$postid = $wp_query->post->ID;
	if ( get_post_meta( $postid, 'post_featpages', true ) == "Yes" ) { ?>
		<?php include (TEMPLATEPATH . '/featured-pages.php'); ?>
	<?php } ?>

	<?php if ( get_post_meta( $postid, 'post_featcontent', true ) == "Full Width Featured Content Slider"  ) { ?>
		<?php include (TEMPLATEPATH . '/featured-wide.php'); ?>
	<?php } ?>

	<div id="page" class="clearfix" style="background-image:none;">

		<div id="alt-home-bottom" class="clearfix maincontent">

			<div class="home-widget-1">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Alt Home Page Widget 1') ) : ?>
				<?php endif; ?>
			</div>

			<div class="home-widget-2">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Alt Home Page Widget 2') ) : ?>
				<?php endif; ?>
			</div>

			<div class="home-widget-3">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Alt Home Page Widget 3') ) : ?>
				<?php endif; ?>
			</div>


		</div>

<?php get_footer(); ?>