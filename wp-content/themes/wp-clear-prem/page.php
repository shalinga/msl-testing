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

<?php if (is_front_page()) {?>
				<?php /* NEWS BOX */ ?>

				<div class="news_section_head"> 
				
					<h2 class="feat-title"><span>News</span></h2>

<?php 
$count = 1;
$my_query = new WP_Query(array(
	'category_name' => 'news',
	'showposts' => '3'
));
while ($my_query->have_posts()) : $my_query->the_post();
$do_not_duplicate[] = $post->ID; ?>



						<div class="entry clearfix newsbody">

							<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php include (TEMPLATEPATH . "/post-thumb.php"); ?></a>
							<h3 class="post-title"><a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h3>
							<?php include (TEMPLATEPATH . "/postinfo.php"); ?>
							<?php if ( get_option('solostream_post_content') == 'Excerpts' ) { ?>
							<?php the_excerpt(); ?>
							<p class="readmore"><a class="more-link" href="<?php the_permalink() ?>" rel="nofollow" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php _e("More", "solostream"); ?></a></p>
							<?php } else { ?>
							<?php the_content(__("More", "solostream")); ?>
							<?php } ?>
<div style="clear:both;"></div>
						</div>

<?php $count = $count + 1 ?>
<?php endwhile; ?>
<div style="clear:both;"></div>
				</div>
		<?php } ?>

<P>
			<?php if ( get_post_meta( $postid, 'post_featgalleries', true ) == "Yes" ) { ?>
				<?php include (TEMPLATEPATH . '/featured-galleries.php'); ?>
			<?php } ?>

			<div id="content" class="maincontent">

				<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } ?>

				<?php include (TEMPLATEPATH . '/banner468.php'); ?>	
<!--shalinga -->	
		
					<?php if (is_front_page()) {?>
						<!--shalinga start-->
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

						
						<?php }else{?>
												
<!--shalinga end-->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div class="singlepage">

					<div class="post clearfix" id="post-main-<?php the_ID(); ?>">

						<div class="entry">

							<h1 class="page-title"><?php the_title(); ?></h1>

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

							<div style="clear:both;"></div>

							<?php wp_link_pages(); ?>

						</div>

					</div>


				</div>

<?php endwhile; endif; ?>
				<?php };?>
<!-- move by shalinga 1st Dec -->
			<?php if ( get_post_meta( $postid, 'post_featvideo', true ) == "Yes" ) { ?>
				<?php include (TEMPLATEPATH . '/featured-vids.php'); ?>
			<?php } ?>

			</div>

			<?php include (TEMPLATEPATH . '/sidebar-narrow.php'); ?>

		</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>