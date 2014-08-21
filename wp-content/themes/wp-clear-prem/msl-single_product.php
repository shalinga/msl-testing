<?php 
global $prefix;
global $post;
global $wpdb;
$p_id = $post->ID;

?>

<?php the_title(); ?>

<?Php
$thumbid = get_post_thumbnail_id($post->ID);
$featuredimage = wp_get_attachment_image_src($thumbid, 'thumbnail');
$featuredimage = $featuredimage[0];

/*
if ($featuredimage){
	$img = "<img src = '$featuredimage' width ='200' height = '200'/>";
} 

echo "<div id = 'product_image'>";
	echo $img;
echo "</div>";

echo "<div id = 'product_data'>";
	echo "<BR>";
	echo $price;
	echo "<BR>";
											
	echo "<a href='http://$link' target='_blank'><input type='button' name='Bye Product' value='Buy Now'></a>";
echo "</div>";
*/
?>
	
<?php the_content(); ?>
