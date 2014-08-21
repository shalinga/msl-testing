<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready(function(){
		jQuery('#slider-nav-galleries').navslide({ 
			display: 1, // how many slides do you want to move at 1 time?
			interval: false, // auto-slide.
			intervaltime: 5000, // auto-slide in milliseconds.
			duration: 500 // how fast must should slides move in ms?
		});
	});
//]]>
</script>

<div id="slider-nav-galleries" class="clearfix">

	<div class="slidenav">

		<?php if ( get_option('solostream_galleries_title') ) { ?>
		<strong><?php echo stripslashes(get_option('solostream_galleries_title')); ?></strong>
		<?php } ?>

		<?php if ( get_option('solostream_galleries_count') != "4" ) { ?>
		<a class="buttons next" href="#">&raquo;</a><a class="buttons prev" href="#">&laquo;</a>
		<?php } ?>

	</div>

	<div class="slideport">

		<ul class="slideview clearfix">

<?php 
$count = 1;
$my_query = new WP_Query(array(
	'category_name' => get_option('solostream_galleries_cat'),
	'showposts' => get_option('solostream_galleries_count')
));
while ($my_query->have_posts()) : $my_query->the_post();
$do_not_duplicate[] = $post->ID; ?>

			<li class="gallery-post" id="gallery-<?php the_ID(); ?>">

				<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php include (TEMPLATEPATH . "/post-thumb.php"); ?></a>

				<p class="gallery-title"><a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php the_title(); ?></a></p>

			</li>

<?php $count = $count + 1 ?>
<?php endwhile; ?>

		</ul>

	</div>

</div>