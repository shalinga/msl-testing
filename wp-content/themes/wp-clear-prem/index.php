<?php get_header(); ?>

	<?php if ( is_home() && $paged < 2 && get_option('solostream_featpage_on') == 'Yes') { ?>
	<?php include (TEMPLATEPATH . '/featured-pages.php'); ?>
	<?php } ?>

	<?php if ( is_home() && $paged < 2 && get_option('solostream_features_on') == 'Full Width Featured Content Slider') { ?>
	<?php include (TEMPLATEPATH . '/featured-wide.php'); ?>
	<?php } ?>

	<div id="page" class="clearfix">

		<div id="contentleft" class="maincontent">

			<?php if ( is_home() && $paged < 2 && get_option('solostream_features_on') == 'Narrow Width Featured Content Slider') { ?>
			<?php include (TEMPLATEPATH . '/featured-narrow.php'); ?>
			<?php } ?>

			<?php if ( is_home() && $paged < 2 && get_option('solostream_videos_on') == 'Yes' ) { ?>
			<?php include (TEMPLATEPATH . '/featured-vids.php'); ?>
			<?php } ?>

			<?php if ( is_home() && $paged < 2 && get_option('solostream_galleries_on') == 'Yes' ) { ?>
			<?php include (TEMPLATEPATH . '/featured-galleries.php'); ?>
			<?php } ?>

			<div id="content" class="clearfix">

				<?php include (TEMPLATEPATH . '/banner468.php'); ?>

				<?php if ( get_option('solostream_home_layout') == 'Option 1 - Standard Blog Layout') { ?>
				<?php include (TEMPLATEPATH . '/index1.php'); ?>
				<?php } elseif ( get_option('solostream_home_layout') == 'Option 2 - 2 Posts Aligned Side-by-Side') { ?>
				<?php include (TEMPLATEPATH . '/index2.php'); ?>
				<?php } elseif ( get_option('solostream_home_layout') == 'Option 3 - Posts Arranged by Category Side-by-Side') { ?>
				<?php include (TEMPLATEPATH . '/index3.php'); ?>
				<?php } elseif ( get_option('solostream_home_layout') == 'Option 4 - Posts Arranged by Category Stacked') { ?>
				<?php include (TEMPLATEPATH . '/index4.php'); ?>
				<?php } ?>

			</div>

			<?php include (TEMPLATEPATH . '/sidebar-narrow.php'); ?>

		</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>