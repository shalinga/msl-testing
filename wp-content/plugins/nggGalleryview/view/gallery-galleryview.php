<?php 
/**
Template Page for the jQuery Galleryview integration

Follow variables are useable :

	$gallery     : Contain all about the gallery
	$images      : Contain all images, path, title
	$pagination  : Contain the pagination content

 You can check the content when you insert the tag <?php var_dump($variable) ?>
 If you would like to show the timestamp of the image ,you can use <?php echo $exif['created_timestamp'] ?>
**/

?>
<!-- gallery Description -->
<div class="album_desc">

<?php 
function getDescBygalleryid($gallery_id) {
    global $wpdb;
$post = $wpdb->get_results("SELECT * FROM ngg_gallery WHERE gid = $gallery_id");
//var_dump($post);
        if ( $post )
		//return get_post($post, $output);
   		//return null;
		return $post;			
}
?>
<!-- gallery Description -->

<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>

<!-- gallery Description -->
<?php
	$galleryid = $gallery->ID;
	$output = getDescBygalleryid($galleryid)->galdesc;
//var_dump($output);
	$id = $output;
//echo $id;
?>
</div>
<!-- gallery Description -->

<div id="<?php echo $gallery->anchor ?>" class="galleryview">

	<!-- Thumbnails -->
	<?php foreach ($images as $image) : ?>		

	<div class="panel">
		<img src="<?php echo $image->imageURL ?>" />
		<div class="panel-overlay">
			<h2><?php echo html_entity_decode ($image->alttext); ?></h2>
			<p><?php echo html_entity_decode ($image->description); ?></p>
		</div>
	</div>
 	<?php endforeach; ?>
  	<ul class="filmstrip">
  	<?php foreach ($images as $image) : ?>	
	    <li><img src="<?php echo $image->thumbnailURL ?>" alt="<?php echo $image->alttext ?>" title="<?php echo $image->alttext ?>" /></li>
	<?php endforeach; ?>
  	</ul>
</div>

<div class="ngg-description">
	<p><?php echo $gallery->galdesc ?></p>
	<?php if ($gallery->counter > 0) : ?>
	<p><strong><?php echo $gallery->counter ?></strong> <?php _e('Photos', 'nggallery') ?></p>
	<?php endif; ?>
</div>

<script type="text/javascript" defer="defer">
	jQuery("document").ready(function(){
		jQuery('#<?php echo $gallery->anchor ?>').galleryView({
			panel_width: 600,
			panel_height: 400,
			frame_width: 60,
			frame_height: 40,
			transition_interval: 0,
			overlay_color: '#222',
			overlay_text_color: 'white',
			caption_text_color: '#222',
			background_color: 'transparent',
			border: 'none',
			nav_theme: 'dark',
			easing: 'easeInOutQuad'
		});
	});
	
</script>

<?php endif; ?>

