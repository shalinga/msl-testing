<div id="feature-vids">

	<?php if (get_option('solostream_videos_title')) { ?>
	<h2 class="feature-title"><span><?php echo stripslashes(get_option('solostream_videos_title')); ?></span></h2>
	<?php } ?>

	<div id="slideshowfeaturevids" class="clearfix">

		<div class="slides clearfix">

			<ul class="clearfix">

<?php 
$count = 1;
$my_query = new WP_Query(array(
	'category_name' => get_option('solostream_videos_cat'),
	'showposts' => get_option('solostream_videos_count')
));
while ($my_query->have_posts()) : $my_query->the_post();
$do_not_duplicate[] = $post->ID;
$embed = get_post_meta( $post->ID, 'video_embed', true ); ?>

				<li id="main-vid-post-<?php echo $count; ?>">
					<div class="feature-vid">
						<?php 
						$alt = preg_match_all('/(width|height)=("[^"]*")/i', $embed, $matches);
						$embed = preg_replace('/(width)=("[^"]*")/i', 'width="370"', $embed);
						$embed = preg_replace('/(height)=("[^"]*")/i', 'height="260"', $embed);
						echo $embed;
						?>
					</div>
				</li>

<?php $count = $count + 1 ?>
<?php endwhile; ?>

			</ul>

		</div>

		<div id="slider-nav">

<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready(function(){
		jQuery('#slider-nav').navslide({
			axis: 'y' // horizontal scroller.
		});
	});
//]]>
</script>

			<div class="slidenav">
				<strong><?php echo stripslashes(get_option('solostream_videos_title')); ?></strong>
				<?php if ( get_option('solostream_videos_count') != "3" ) { ?>
				<a class="buttons next" href="#">&raquo;</a><a class="buttons prev" href="#">&laquo;</a>
				<?php } ?>
			</div>

			<div class="slideport">

				<ul class="slideview slides-nav">

<?php 
$count = 1;
$my_query = new WP_Query(array(
	'category_name' => get_option('solostream_videos_cat'),
	'showposts' => get_option('solostream_videos_count')
));
while ($my_query->have_posts()) : $my_query->the_post();
$do_not_duplicate[] = $post->ID; ?>

					<li class="clearfix<?php if ( $count == 1 ) { ?> on<?php } ?>">

						<?php if ( function_exists('get_the_image')) { 
							if (get_option('solostream_default_thumbs') == 'yes') { $defthumb = get_bloginfo('stylesheet_directory') . '/images/def-thumb.jpg'; } else { $defthumb == 'false'; }
							$solostream_img = get_the_image(array(
								'meta_key' => 'thumbnail-vids',
								'size' => 'medium',
								'image_class' => 'thumbnail-vids',
								'default_image' => $defthumb,
								'format' => 'array',
								'image_scan' => true,
								'link_to_post' => false, ));
							if ( $solostream_img['url'] && get_option('solostream_show_thumbs') == 'yes' && get_post_meta( $post->ID, 'remove_thumb', true ) != 'Yes' ) { ?> 
								<a href="#main-vid-post-<?php echo $count; ?>" title="<?php _e("Watch", "solostream"); ?> <?php the_title(); ?>">
									<img class="<?php echo $solostream_img['class']; ?>" src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $solostream_img['url']; ?>&amp;w=52&amp;h=52&amp;zc=1" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />
								</a>
							<?php } 
						} ?>
					
						<div class="excerpt">
							<span class="view" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>" onclick="location.href='<?php the_permalink() ?>';">
								<?php the_title(); ?>
							</span><?php the_excerpt(); ?>
						</div>					
					</li>

<?php $count = $count + 1 ?>
<?php endwhile; ?>

				</ul>

			</div>

		</div>

	</div>

</div>