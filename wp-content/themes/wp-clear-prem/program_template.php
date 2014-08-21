<?php
/*
Template Name: Program Template
*/
?>
	
<?php get_header(); ?>

	<?php
	global $wp_query;
	$postid = $wp_query->post->ID;
?>
	
	<div id="page" class="clearfix">

		<div id="contentleft">

			<div id="content" class="maincontent">


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div class="singlepage">

					<div class="post clearfix" id="post-main-<?php the_ID(); ?>">

						<div class="entry">

					 	<h1 class="page-title"><?php the_title(); ?></h1>

							<?php //the_content(); 
							$posttype = $wp_query->post->post_content;?>

							<div> 
			<?php 
			$count = 1;
			$my_query = new WP_Query(array(
				'category_name' => $posttype,
				'showposts' => '3'
			));
			while ($my_query->have_posts()) : $my_query->the_post();
			$do_not_duplicate[] = $post->ID; ?>

								<div id="post-1-<?php the_ID(); ?>">

									<div class="programtepm">

										<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php include (TEMPLATEPATH . "/post-thumb.php"); ?></a>
										<h3 class="post-title"><a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h3>
										<?php include (TEMPLATEPATH . "/postinfo.php"); ?>
										<?php if ( get_option('solostream_post_content') == 'Excerpts' ) { ?>
										<?php the_excerpt(); ?>
										<p class="readmore"><a class="more-link" href="<?php the_permalink() ?>" rel="nofollow" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php _e("More", "solostream"); ?></a></p>
										<?php } else { ?>
										<?php the_content(__("More", "solostream")); ?>
										<?php } ?>
									</div>

								</div>
			<?php $count = $count + 1 ?>
			<?php endwhile; ?>

							</div>

							<div style="clear:both;"></div>

						</div>

					</div>

				</div>

<?php endwhile; endif; ?>
			
			</div>

			<?php //include (TEMPLATEPATH . '/sidebar-narrow.php'); ?>

		</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>