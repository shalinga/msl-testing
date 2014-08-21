<?php
/*
Template Name: Sales Letter Template 1
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title(' '); ?> <?php if(wp_title(' ', false)) { echo ' : '; } ?><?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/sales-letter.css" type="text/css" media="screen" />

</head>
<!-- Shalinga -->

<?php
//code copy from sdk facebook file
require 'facebook.php';

// Create our Application instance (replace this with your appId and secret).

$facebook = new Facebook(array(
  'appId'  => '269287039782328',
  'secret' => '5bae1f7941e82a43d92d14536cf8746f',
));
//$data = $facebook->getSignrequest();

?>
<!-- Shalinga -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
	function new_excerpt_length($length) {
	return 30;
	}
	add_filter('excerpt_length', 'new_excerpt_length');
				
?>

<body class="sales-letter1">

<!-- Shalinga facebook strat-->
<!--
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=314052525279490";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
-->
<!-- Shalinga facebook End-->


<div class="post clearfix fbpage">
	<?php /* <h1 class="page-title"><?php the_title(); ?></h1> */ ?>
	<h2><b> Featured Gallery </b></h2>
	<?php the_content(); ?>

<h2><b> Featured Video </b></h2>
<div class="vdo">
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
<?php if ($count == 1) {; ?>
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
<?php } ?>
<?php endwhile; ?>
			</ul>
</div>
<!-- end of vdo -->

<h2><b> News Updates </b></h2>
<div class="news">
<P>
<ul>
<?php

$args = array( 'numberposts' => 2, 'orderby' => 'post_date','post_type' => 'post','post_status' => 'publish' );
$myposts = get_posts( $args );

foreach( $myposts as $post ) :	setup_postdata($post); ?>
	<li><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></li>
<?php
$thumbid = get_post_thumbnail_id($post->ID);
$featuredimage = wp_get_attachment_image_src($thumbid, 'thumbnail');
$featuredimage = $featuredimage[0];
$img = "<img src = '$featuredimage'/>";
echo $img;
?>
<?php the_excerpt(); ?>
<?php endforeach; ?>
</ul>

</div> <!-- End news -->

<div id="footer1" class="clearfix1">
<!--	&copy; <?php bloginfo('name'); ?> <?php echo date('Y'); ?>. <?php _e("All rights reserved.", "solostream"); ?> -->
</div>

<?php endwhile; endif; ?>

<?php wp_footer(); ?> 

</body>

</html>