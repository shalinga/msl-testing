<div id="home-top-narrow" class="clearfix maincontent">

	<div id="slideshow" class="clearfix">

		<?php if (get_option('solostream_features_title')) { ?>
		<h2 class="feature-title"><span><?php echo stripslashes(get_option('solostream_features_title')); ?></span></h2>
		<?php } ?>

		<div class="slides clearfix">

			<ul class="clearfix">

<?php 
$count = 1;
$featurecount = get_option('solostream_features_number'); 
$my_query = new WP_Query("tag=featured&showposts=$featurecount");
while ($my_query->have_posts()) : $my_query->the_post();
$do_not_duplicate[] = $post->ID; ?>

				<li id="main-post-<?php echo $count; ?>"<?php if ( has_tag('full-image') ) { ?> class="full-width"<?php }?>>

					<div class="entry clearfix entry-clearfix">

<?php if ( get_post_meta( $post->ID, 'video_embed', true ) ) { ?>

						<div class="feature-video">
							<?php $data = get_post_meta( $post->ID, 'video_embed', true );
							$alt = preg_match_all('/(width|height)=("[^"]*")/i', $data, $matches);
							$data = preg_replace('/(width)=("[^"]*")/i', 'width="290"', $data);
							$data = preg_replace('/(height)=("[^"]*")/i', 'height="240"', $data);
							echo $data;
							?>
						</div>

<?php } else { ?>

						<?php if ( function_exists('get_the_image')) {
						if (get_option('solostream_default_features') == 'yes') { 
							$defthumb = get_bloginfo('stylesheet_directory') . '/images/def-thumb3.jpg'; 
						} else { 
							$defthumb == 'false'; 
						}
						$solostream_img = get_the_image(array(
							'meta_key' => 'home_feature',
							'size' => 'full',
							'image_class' => 'home_feature',
							'default_image' => $defthumb,
							'format' => 'array',
							'image_scan' => true,
							'link_to_post' => false, ));
						if ( $solostream_img['url'] && get_post_meta( $post->ID, 'remove_thumb', true ) != 'Yes' ) { ?>

						<div class="feature-image"> 
							<?php if ( has_tag('full-image') ) { ?>
							<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><img class="<?php echo $solostream_img['class']; ?>" src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $solostream_img['url']; ?>&amp;w=630&amp;h=290&amp;zc=1" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /></a>
							<?php } else { ?>
							<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><img class="<?php echo $solostream_img['class']; ?>" src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $solostream_img['url']; ?>&amp;w=290&amp;h=240&amp;zc=1" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /></a>
							<?php } ?>
						</div>
						<?php } } ?>

<?php } ?>
					<?php if ($post->post_content!=""){ ?>
						<div class="feat-content new-feat-content">
							<h2 class="post-title new-post-title"><a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
							<?php if ( get_option('solostream_post_content') == 'Excerpts' ) { ?>
							<?php the_excerpt(); ?>
							<p class="readmore"><a class="more-link" href="<?php the_permalink() ?>" rel="nofollow" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php _e("More", "solostream"); ?></a></p>
							<?php } else { ?>
							<?php the_content(__("More", "solostream")); ?>
							<?php } ?>
						</div>
					<?php } ?>

					</div>

					<?php include (TEMPLATEPATH . "/postinfo.php"); ?>

					<div style="clear:both;"></div>

				</li>

<?php $count = $count + 1 ?>
<?php endwhile; ?>

			</ul>

		</div>

		<div class="slides-nav-container">

			<ul class="slides-nav">

<?php 
$count = 1;
$featurecount = get_option('solostream_features_number'); 
$my_query = new WP_Query("tag=featured&showposts=$featurecount");
while ($my_query->have_posts()) : $my_query->the_post();
$do_not_duplicate[] = $post->ID; ?>

				<li id="nav-post-<?php echo $count; ?>" class="clearfix<?php if ( $count == 1 ) { ?> on<?php } ?>">
					<a href="#main-post-<?php echo $count; ?>" title="<?php the_title(); ?>">
						<?php include (TEMPLATEPATH . "/post-thumb.php"); ?>
					</a>
				</li>

<?php $count = $count + 1 ?>
<?php endwhile; ?>

			</ul>

		</div>

	</div>

</div>