<?php get_header(); ?>

	<div id="page" class="clearfix">

		<div id="contentleft">

			<div id="content" class="maincontent">

				<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } ?>

				<?php include (TEMPLATEPATH . '/banner468.php'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div class="singlepost">

					<div class="post" id="post-main-<?php the_ID(); ?>">

						<div class="entry">

							<h1 class="page-title" style="text-align:center;"><?php the_title(); ?></h1>

							<?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?>

							<?php the_content(); ?>

							<p>
								<?php previous_image_link('false', '&laquo; Previous Image | ') ?>
								<a title="open the full-size image in a new window" href="<?php echo wp_get_attachment_url($post->ID); ?>" target="popupWindow" onclick="window.open(this.href, this.name,'width=1000,height=600,resizable=yes,scrollbars=yes,menubar=no,status=no'); return false;">Full-Size Image</a>&nbsp;|&nbsp;
								<a href="<?php echo get_permalink($post->post_parent); ?>" rel="bookmark">Main Gallery Page</a>
								<?php next_image_link('false', ' | Next Image &raquo;') ?>
							</p> 

							<!-- To display current image in the photo gallery -->
							<?php $image_attributes = wp_get_attachment_image_src( $post->ID, 'full' ); ?>
							<div><a title="open the full-size image in a new window" href="<?php echo wp_get_attachment_url($post->ID); ?>" target="popupWindow" onclick="window.open(this.href, this.name,'width=1000,height=600,resizable=yes,scrollbars=yes,menubar=no,status=no'); return false;"><img src="<?php echo $image_attributes[0] ?>" alt="<?php the_title(); ?>"></a></div>

						</div>

						<div class="gallery-nav clearfix">
							<h3 style="text-align:center;">Other Images in this Gallery</h3>
							<?php echo do_shortcode('[gallery id="'.$post->post_parent.'" size="thumbnail" columns="4"]'); ?>
						</div>

						<?php comments_template('', true); ?>

					</div>

				</div>

<?php endwhile; endif; ?>

			</div>

		</div>

<?php get_footer(); ?>