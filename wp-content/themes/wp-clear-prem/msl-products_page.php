<?php
/*
Template Name: MSL Products 1
*/
?>

<?php get_header(); ?>

<P>

<div class="post clearfix">
	<!-- <div class="entry"> -->

			<?php

				$args = array(
					'post_type' => 'bc_msl',
					'post_status' 	=> 'publish',
					'numberposts' 	=> -1);

						$userpost = get_posts($args);

							foreach($userpost as $post)	{
								$title =  $post->post_title;
								$content =  $post->post_content;

								//$currentpost = get_post_custom($post->ID);

								$price = get_post_meta($post->ID,'bc_price', true);
								$link = get_post_meta($post->ID,'bc_link', true);
									$thumbid = get_post_thumbnail_id($post->ID);
									$featuredimage = wp_get_attachment_image_src($thumbid, 'thumbnail');
									$featuredimage = $featuredimage[0];

									if ($featuredimage){
										$img = "<img src = '$featuredimage' width ='200' height = '200'/>";
									} else{
										//$img = "<img src = 'wp-content/themes/shalinga/images/no_profile_pic.jpg' width ='108' height = '144'/>";
									}

										echo "<div class='product_main'>";
									?>
									
					<a href="<?php the_permalink() ?>"  title="<?php the_title(); ?>"><?php include (TEMPLATEPATH . "/msl-single_product.php"); ?></a>
									
									<?php
											echo "<div id = 'product_image'>";
												echo $img;
											echo "</div>";

											echo "<div id = 'product_data'>";
												echo $content;
												echo "<BR>";
												echo $price;
												echo "<BR>";
													if ($link){
														echo "<a href='http://$link' target='_blank'><input type='button' class='msl_buy_button' name='Bye Product' value='Buy'></a>";
													}
									
											echo "</div>";
											echo "<BR>";
										echo "</div>"; 
									
								} 
								?>

<?php  get_sidebar(); ?>
<?php get_footer(); ?>