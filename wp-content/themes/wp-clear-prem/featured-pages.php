<div id="alt-home-top" class="clearfix maincontent">

	<div id="slideshowpages" class="clearfix">

		<div class="slides clearfix">

			<ul class="clearfix">

<?php 
$featpages = get_option('solostream_featpage_ids');
$featarr=split(",",$featpages);
$featarr = array_diff($featarr, array(""));
$count = 1;
foreach ( $featarr as $featitem ) { ?>

<?php $my_query = new WP_Query(array(
	'page_id' => $featitem
	));
while ($my_query->have_posts()) : $my_query->the_post(); ?>

				<li id="main-post-<?php echo $count; $count++; ?>"<?php if ( has_tag('full-image') ) { ?> class="full-width"<?php }?>>

					<div class="entry clearfix">

<?php if ( get_post_meta( $post->ID, 'video_embed', true ) ) { ?>

						<div class="feature-video">
							<?php $data = get_post_meta( $post->ID, 'video_embed', true );
							$alt = preg_match_all('/(width|height)=("[^"]*")/i', $data, $matches);
							$data = preg_replace('/(width)=("[^"]*")/i', 'width="500"', $data);
							$data = preg_replace('/(height)=("[^"]*")/i', 'height="250"', $data);
							echo $data;
							?>
						</div>

<?php } else { ?>

						<?php if ( function_exists('get_the_image')) {
						if ( get_option('solostream_default_features') == 'yes' && has_tag('full-image') ) { 
							$defthumb = get_bloginfo('stylesheet_directory') . '/images/def-thumb3.jpg'; 
						} elseif ( get_option('solostream_default_features') == 'yes' && !has_tag('full-image') ) { 
							$defthumb = get_bloginfo('stylesheet_directory') . '/images/def-thumb2.jpg'; 
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
							<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><img class="<?php echo $solostream_img['class']; ?>" src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $solostream_img['url']; ?>&amp;w=960&amp;h=300&amp;zc=1" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /></a>
							<?php } else { ?>
							<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><img class="<?php echo $solostream_img['class']; ?>" src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $solostream_img['url']; ?>&amp;w=500&amp;h=250&amp;zc=1" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /></a>
							<?php } ?>
						</div>
						<?php } } ?>

<?php } ?>

						<div class="feat-content">
							<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>

							<?php /* include (TEMPLATEPATH . "/postinfo.php"); */ ?>

							<?php if ( get_option('solostream_post_content') == 'Excerpts' ) { ?>
							<?php the_excerpt(); ?>
							<p><a class="more-link" href="<?php the_permalink() ?>" rel="nofollow" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php _e("Continue Reading", "solostream"); ?></a></p>
							<?php } else { ?>
							<?php the_content(__("Continue Reading", "solostream")); ?>
							<?php } ?>
						</div>

					</div>

					<div style="clear:both;"></div>

				</li>

<?php endwhile; ?>
<?php } ?>

			</ul>

		</div>


		<div class="slides-nav-container">

			<ul class="slides-nav">

<?php 
$featpages = get_option('solostream_featpage_ids');
$featarr=split(",",$featpages);
$featarr = array_diff($featarr, array(""));
$count = 1;
foreach ( $featarr as $featitem ) { ?>

<?php $my_query = new WP_Query(array(
	'page_id' => $featitem
	));
while ($my_query->have_posts()) : $my_query->the_post(); ?>

				<li id="nav-post-<?php echo $count; ?>" class="clearfix<?php if ( $count == 1 ) { ?> on<?php } ?>">
					<a href="#main-post-<?php echo $count; $count ++; ?>" title="<?php the_title(); ?>">
						<?php the_title(); ?>
					</a>
				</li>

<?php endwhile; ?>
<?php } ?>

			</ul>

		</div>

	</div>

</div>