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

	<div id="page" class="clearfix">

		<div id="contentleft">

			<?php if ( get_post_meta( $postid, 'post_featcontent', true ) == "Narrow Width Featured Content Slider" ) { ?>
				<?php include (TEMPLATEPATH . '/featured-narrow.php'); ?>
			<?php } ?>

			<?php if ( get_post_meta( $postid, 'post_featvideo', true ) == "Yes" ) { ?>
				<?php include (TEMPLATEPATH . '/featured-vids.php'); ?>
			<?php } ?>

			<?php if ( get_post_meta( $postid, 'post_featgalleries', true ) == "Yes" ) { ?>
				<?php include (TEMPLATEPATH . '/featured-galleries.php'); ?>
			<?php } ?>

			<div id="content" class="maincontent">

				<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } ?>

				<?php include (TEMPLATEPATH . '/banner468.php'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div class="singlepost">

					<div class="post" id="post-main-<?php the_ID(); ?>">

						<div class="entry">

							<h1 class="post-title single"><a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h1>

							<?php include (TEMPLATEPATH . '/postinfo.php'); ?>

							<?php 
							$videoembed = get_post_meta( $post->ID, 'video_embed', true );
							$mylayout = get_post_meta( $post->ID, 'layout', true );
							if ( $videoembed ) { ?>
							<div class="single-video">
								<?php $embed = $videoembed;
								if ( $mylayout && $mylayout !== 'Default' ) { 
									$layout = $mylayout; 
								} else { 
									$layout = get_option('solostream_layout'); 
								}
								$alt = preg_match_all('/(width|height)=("[^"]*")/i', $embed, $matches);
								if ( $layout == "Content | Sidebar-Narrow | Sidebar-Wide" || $layout == "Sidebar-Narrow | Content | Sidebar-Wide" || $layout == "Sidebar-Wide | Sidebar-Narrow | Content" || $layout == "Sidebar-Wide | Content | Sidebar-Narrow" ) { 
									$embed = preg_replace('/(width)=("[^"]*")/i', 'width="438"', $embed);
									$embed = preg_replace('/(height)=("[^"]*")/i', 'height="320"', $embed);
								} elseif  ( $layout == "Full-Width" ) {
									$embed = preg_replace('/(width)=("[^"]*")/i', 'width="918"', $embed);
									$embed = preg_replace('/(height)=("[^"]*")/i', 'height="570"', $embed);
								} else {
									$embed = preg_replace('/(width)=("[^"]*")/i', 'width="588"', $embed);
									$embed = preg_replace('/(height)=("[^"]*")/i', 'height="370"', $embed);
								}
								echo $embed;
								?>
							</div>
							<?php } ?>

							<?php the_content(); ?>



<!-- Start Share FB twitter -->
<div class="fbtw">
<div class="fb">

<a name="fb_share" type="button" share_url= "<?php the_permalink() ?>"></a> 
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript">
							</script>
</div>
<div class="space">&nbsp;</div>
<div class="wt">
<iframe allowtransparency="true" frameborder="0" scrolling="no"
        src="//platform.twitter.com/widgets/tweet_button.html"
        style="width:130px; height:20px;"></iframe>
</div>
</div>
<!-- End Share FB twitter -->
							<div style="clear:both;"></div>

							<?php wp_link_pages(); ?>

						<!--	<?php if(function_exists('the_tags')) { the_tags('<p class="tags"><strong>'. __('Tags', "solostream"). ': </strong> ', ', ', '</p>'); } ?>
							<p class="cats"><strong><?php _e('Category', "solostream"); ?></strong>: <?php the_category(', '); ?></p>
-->

						</div>

						<?php include (TEMPLATEPATH . '/auth-bio.php'); ?>

						<?php //include (TEMPLATEPATH . '/related.php'); ?>

						<?php //comments_template('', true); ?>

					</div>

					<?php // include (TEMPLATEPATH . "/bot-nav.php"); ?>

				</div>

<?php endwhile; endif; ?>

			</div>

			<?php include (TEMPLATEPATH . '/sidebar-narrow.php'); ?>

		</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>