<?php 
/**
Template Page for the album overview (extended)

Follow variables are useable :

	$album     	 : Contain information about the album
	$galleries   : Contain all galleries inside this album
	$pagination  : Contain the pagination content

 You can check the content when you insert the tag <?php var_dump($variable) ?>
 If you would like to show the timestamp of the image ,you can use <?php echo $exif['created_timestamp'] ?>
**/

/**
* Retrieve a post given its title.
*
* @uses $wpdb
*
* @param string $post_title Page title
* @param string $output Optional. Output type. OBJECT, ARRAY_N, or ARRAY_A.
* @return mixed
*/
?>

<?php
$postid = get_the_ID();
//echo $postid;
?>

<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($galleries)) : ?>
		
<div class="ngg-albumoverview">	
	<!-- List of galleries -->
	<?php foreach ($galleries as $gallery) : ?>

	<?php
	$title = $gallery->title;
	$output = getPostByTitle($title)->ID;
	$id = $output;
	$permalink = get_permalink( $id ); 
 	?>

<?php if ($postid == '1918'){?>
	<div class="ngg-album">
		<div class="ngg-albumtitle"><a href="<?php echo $permalink ?>" target="_blank"><?php echo $gallery->title ?></a></div>
			<div class="ngg-albumcontent">
				<div class="ngg-thumbnail">
					<a href="<?php echo $permalink ?>" target="_blank"><img class="Thumb" alt="<?php echo $gallery->title ?>" src="<?php echo $gallery->previewurl ?>"/></a>
				</div>
				<div class="ngg-description">
				<p><?php echo $gallery->galdesc ?></p>
				<?php if ($gallery->counter > 0) : ?>
				<p><strong><?php echo $gallery->counter ?></strong> <?php _e('Photos', 'nggallery') ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php }else{?>
	<div class="ngg-album">
		<div class="ngg-albumtitle"><a href="<?php echo $permalink ?>"><?php echo $gallery->title ?></a></div>
			<div class="ngg-albumcontent">
				<div class="ngg-thumbnail">
					<a href="<?php echo $permalink ?>"><img class="Thumb" alt="<?php echo $gallery->title ?>" src="<?php echo $gallery->previewurl ?>"/></a>
				</div>
				<div class="ngg-description">
				<p><?php echo $gallery->galdesc ?></p>
				<?php if ($gallery->counter > 0) : ?>
				<p><strong><?php echo $gallery->counter ?></strong> <?php _e('Photos', 'nggallery') ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php }?>

 	<?php endforeach;?>
	<!-- Pagination -->
 	<?php echo $pagination ?>
 </div>
<?php endif; ?>