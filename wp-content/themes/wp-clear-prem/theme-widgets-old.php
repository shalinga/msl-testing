<?php

/*-----------------------------------------------------------------------------------*/
// This starts the Side Tabs widget.
/*-----------------------------------------------------------------------------------*/

add_action( 'widgets_init', 'sidetabs_load_widgets' );

function sidetabs_load_widgets() {
	register_widget( 'Sidetabs_Widget' );
}

class Sidetabs_Widget extends WP_Widget {

	function Sidetabs_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sidetabs', 'description' => __('Adds the Side Tabs box for popular posts, archives, categories and tags.', "solostream") );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sidetabs-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'sidetabs-widget', __('Side Tabs Widget', "solostream"), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		/* Before widget (defined by themes). */
		echo $before_widget; ?>

		<div id="widget-tabs">

			<ul class="tabs clearfix">
				<li><a href="#tabs-popular"><?php _e("Popular", "solostream"); ?><span></span></a></li>
				<li><a href="#tabs-recent"><?php _e("Recent", "solostream"); ?><span></span></a></li>
				<li><a href="#tabs-comments"><?php _e("Comments", "solostream"); ?><span></span></a></li>
				<li><a href="#tabs-archives"><?php _e("Archives", "solostream"); ?><span></span></a></li>
			</ul>

			<div style="clear:both;"></div>

			<div id="tabs-popular" class="cat_content clearfix">
				<?php if ( function_exists('get_mostpopular') ) : ?>
					<div class="popular"><?php get_mostpopular("range=weekly&limit=5&order_by=avg&stats_comments=0&pages=0"); ?></div>
				<?php else : ?>
					<ul><li><?php _e("This feature has not been activated yet.", "solostream"); ?></li></ul>
				<?php endif; ?>
			</div>

			<div id="tabs-recent" class="cat_content clearfix">
				<ul>
					<?php
					$numposts = 5;
					$my_query = new WP_Query(array(
						'showposts' => $numposts
					));
					while ($my_query->have_posts()) : $my_query->the_post(); ?>
					<li>
						<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark"); ?>" title="<?php _e("Permanent Link to"); ?> <?php the_title(); ?>"><?php the_title(); ?></a>
					</li>
					<?php endwhile; ?>
				</ul>
			</div>

			<div id="tabs-comments" class="cat_content clearfix side-recent-comments">
				<?php
				$pre_HTML ="";
				$post_HTML ="";
				global $wpdb;
				$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,100) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 5";

				$comments = $wpdb->get_results($sql);
				$output = $pre_HTML;
				$output .= "\n<ul>";
				foreach ($comments as $comment) {
					$output .= "\n<li><a href=\"" . get_permalink($comment->ID)."#comment-" . $comment->comment_ID . "\" title=\"on ".$comment->post_title . "\">" . strip_tags($comment->comment_author) .": " . strip_tags($comment->com_excerpt)."</a></li>";
				}
				$output .= "\n</ul>";
				$output .= $post_HTML;
				echo $output;
				?>
			</div>

			<div id="tabs-archives" class="cat_content clearfix">
				<ul class="side-arc">
					<li class="clearfix">
						<p class="title"><?php _e("Archives", "solostream"); ?>:</p>
						<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
							<option value=""><?php echo attribute_escape(__('Select Month')); ?></option>
							<?php wp_get_archives('type=monthly&format=option&show_post_count=1'); ?>
						</select>
						<noscript><input type="submit" value="<?php _e("Go", "solostream"); ?>" /></noscript>
					</li>
					<li class="clearfix">
						<p class="title"><?php _e("Categories", "solostream"); ?>:</p>
						<form action="<?php bloginfo('url'); ?>/" method="get">
							<?php $select = wp_dropdown_categories('show_option_none=' . __('Select Category') .'&show_count=1&orderby=name&echo=0&hierarchical=1');
							$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
							echo $select;
							?>
							<noscript><input type="submit" value="<?php _e("Go", "solostream"); ?>" /></noscript>
						</form>
					</li>
					<li class="clearfix">
						<p class="title"><?php _e("Tags", "solostream"); ?>:</p>
						<select name="tag-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
							<option value=""><?php echo attribute_escape(__('Select a Tag')); ?></option>
							<?php $posttags = get_tags('orderby=count&order=DESC&number=200'); ?>
							<?php if ($posttags) {
								foreach($posttags as $tag) {
									echo "<option value='";
									echo get_tag_link($tag);
									echo "'>";
									echo $tag->name;
									echo " (";
									echo $tag->count;
									echo ")";
									echo "</option>"; }
							} ?>
						</select>
						<noscript><input type="submit" value="<?php _e("Go", "solostream"); ?>" /></noscript>
					</li>
				</ul>
			</div>

		</div>

		<?php /* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		return $instance;
	}
}


/*-----------------------------------------------------------------------------------*/
// This starts the Subscribe Box widget.
/*-----------------------------------------------------------------------------------*/

add_action( 'widgets_init', 'subscribebox_load_widgets' );

function subscribebox_load_widgets() {
	register_widget( 'SubscribeBox_Widget' );
}

class SubscribeBox_Widget extends WP_Widget {

	function SubscribeBox_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'subscribebox', 'description' => __('Adds the Subscribe Box and/or social network icons.', "solostream") );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'subscribebox-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'subscribebox-widget', __('Subscribe Box Widget', "solostream"), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$intro = $instance['intro'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		printf( '<div class="textwidget">' );

		/* Display intro from widget settings if one was input. */
		if ( $intro )
			printf( '<p>' . __('%1$s', "solostream") . '</p>', $intro ); ?>

			<?php if ( get_option('solostream_subscribe_settings') == 'Use Google/FeedBurner Email' && get_option('solostream_fb_feed_id') ) { ?>

			<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo get_option('solostream_fb_feed_id'); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
				<input type="hidden" value="<?php echo get_option('solostream_fb_feed_id'); ?>" name="uri"/>
				<input type="hidden" name="loc" value="en_US"/>
				<p class="email-form">
					<input type="text" class="sub" name="email" value="<?php _e("email address", "solostream"); ?>" onfocus="if (this.value == '<?php _e("email address", "solostream"); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e("email address", "solostream"); ?>';}" /><input type="submit" value="<?php _e("submit", "solostream"); ?>" class="subbutton" />
				</p>
				<div style="clear:both;"><small><?php _e("Privacy guaranteed. We never share your info.", "solostream"); ?></small></div>
			</form>

			<?php } elseif ( get_option('solostream_subscribe_settings') == 'Use Alternate Email List Form' && get_option('solostream_alt_email_code') ) { ?>

			<?php echo stripslashes(get_option('solostream_alt_email_code')); ?>

			<?php } ?>

		<?php include (TEMPLATEPATH . '/sub-icons.php'); ?>

		<?php printf( '</div>' ); ?>

		<?php 
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and intro to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['intro'] = strip_tags( $new_instance['intro'] );

		return $instance;
	}

	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Subscribe', "solostream"), 'intro' => __('Enter your email address below to receive updates each time we publish new content.', "solostream") );

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', "solostream"); ?></label>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>

		<!-- Intro: Text Input -->
		<p><label for="<?php echo $this->get_field_id( 'intro' ); ?>"><?php _e('Introduction:', "solostream"); ?></label>
		<textarea rows="3" id="<?php echo $this->get_field_id( 'intro' ); ?>" name="<?php echo $this->get_field_name( 'intro' ); ?>" style="width:100%;"><?php echo $instance['intro']; ?></textarea></p>

	<?php
	}
}

/*-----------------------------------------------------------------------------------*/
// This starts the Category Posts widget.
/*-----------------------------------------------------------------------------------*/

add_action( 'widgets_init', 'catposts_load_widgets' );

function catposts_load_widgets() {
	register_widget( 'Catposts_Widget' );
}

class Catposts_Widget extends WP_Widget {

	function Catposts_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'catposts', 'description' => __('Adds posts from a specific category .', "solostream") );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'catposts-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'catposts-widget', __('Category Posts Widget', "solostream"), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $post;
		$post_old = $post; // Save the post object.
	
		extract( $args );
	
		// If no title, use the name of the category.
		if( !$instance["title"] ) {
			$category_info = get_category($instance["cat"]);
			$instance["title"] = $category_info->name;
		}
	
		// Get array of post info.
		$cat_posts = new WP_Query("showposts=" . $instance["num"] . "&cat=" . $instance["cat"]);

		/* Before widget (defined by themes). */
		echo $before_widget;

		// Widget title
		echo $before_title;
		if( $instance["title_link"] )
			echo '<a href="' . get_category_link($instance["cat"]) . '">' . $instance["title"] . '</a>';
		else
			echo $instance["title"];
		echo $after_title;

		// Post list
		echo "<div class='cat-posts-widget textwidget'>\n";
	
		while ( $cat_posts->have_posts() )
		{
			$cat_posts->the_post();
		?>
				<div class="post">

					<div class="entry clearfix">

						<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php include (TEMPLATEPATH . "/post-thumb.php"); ?></a>

						<p class="post-title"><a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php the_title(); ?></a></p>

						<?php the_excerpt(); ?>

					</div>

					<?php include (TEMPLATEPATH . "/postinfo.php"); ?>

					<div style="clear:both;"></div>

				</div>
		<?php
		}

		echo "</div>\n";
	
		echo $after_widget;

		remove_filter('excerpt_length', $new_excerpt_length);
	
		$post = $post_old; // Restore the post object.
}

	function form($instance) {
	?>
		<p>
			<label for="<?php echo $this->get_field_id("title"); ?>">
				<?php _e( 'Title' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
			</label>
		</p>
		
		<p>
			<label>
				<?php _e( 'Category' ); ?>:
				<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("cat"), 'selected' => $instance["cat"] ) ); ?>
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id("num"); ?>">
				<?php _e('Number of Posts to Show'); ?>:
				<input style="text-align: center;" id="<?php echo $this->get_field_id("num"); ?>" name="<?php echo $this->get_field_name("num"); ?>" type="text" value="<?php echo absint($instance["num"]); ?>" size='3' />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id("title_link"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("title_link"); ?>" name="<?php echo $this->get_field_name("title_link"); ?>"<?php checked( (bool) $instance["title_link"], true ); ?> />
				<?php _e( 'Make widget title link' ); ?>
			</label>
		</p>	
	
	<?php
	}
}

/*-----------------------------------------------------------------------------------*/
// This starts the YouTube Videos widget.
/*-----------------------------------------------------------------------------------*/

add_action( 'widgets_init', 'videoslide_load_widgets' );

function videoslide_load_widgets() {
	register_widget( 'VideoSlide_Widget' );
}

class VideoSlide_Widget extends WP_Widget {

	function VideoSlide_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'videoslide', 'description' => __('Adds the YouTube Video slider.', "solostream") );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'videoslide-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'videoslide-widget', __('YouTube Video Widget', "solostream"), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$numvids = $instance['numvids'];
		$ytrss = $instance['ytrss'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title; ?>

<div id="slideshowvids" class="clearfix">

	<div class="slides clearfix">

		<ul class="clearfix">

<?php
$count = 1;
// Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/rss.php');
$rss = fetch_rss($instance['ytrss']);
$maxitems = $instance['numvids']; //set number of items to display
$items = array_slice($rss->items, 0, $maxitems); ?>

<?php 
foreach ( $items as $item ) :
$youtubeid = strchr($item['link'],'=');
$youtubeid = substr($youtubeid,1);
?>

			<li id="vid-post-<?php echo $count; ?>" class="vid-post">
				<object type="application/x-shockwave-flash" data="http://www.youtube.com/v/<?php echo $youtubeid ?>&amp;hl=en_US&amp;fs=1&amp;rel=0" style="display:block;width:300px;height:250px;">
					<param name="allowFullScreen" value="true" />
					<param name="allowscriptaccess" value="always" />>
					<param name="movie" value="http://www.youtube.com/v/<?php echo $youtubeid ?>&amp;hl=en_US&amp;fs=1&amp;rel=0" />
				</object>
			</li>

<?php $count = $count + 1; endforeach; ?>

		</ul>

	</div>


	<ul class="slides-nav">

<?php
$count = 1;
// Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/rss.php');
$rss = fetch_rss($instance['ytrss']);
$maxitems = $instance['numvids']; //set number of items to display
$items = array_slice($rss->items, 0, $maxitems); ?>

<?php 
foreach ( $items as $item ) :
$youtubeid = strchr($item['link'],'=');
$youtubeid = substr($youtubeid,1);
?>
		<li id="vids-nav-post-<?php echo $count; ?>" class="vid-post-title clearfix">
			<a href="#vid-post-<?php echo $count; ?>" title="<?php the_title(); ?>">
				<?php echo $item['title']; ?>
			</a>
		</li>

<?php 
$count = $count + 1; 
endforeach; ?>

	</ul>

</div>

		<?php 
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Recent YouTube Videos', "solostream"), 
			'numvids' => '5',
			'ytrss' => 'http://gdata.youtube.com/feeds/base/users/mdp8593/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile');

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', "solostream"); ?></label>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>

		<p><label for="<?php echo $this->get_field_id( 'numvids' ); ?>"><?php _e('Number of YouTube videos to show:', "solostream"); ?></label>
		<input id="<?php echo $this->get_field_id( 'numvids' ); ?>" name="<?php echo $this->get_field_name( 'numvids' ); ?>" value="<?php echo $instance['numvids']; ?>" style="width:100%;" /></p>

		<!-- numvids: Text Input -->
		<p><label for="<?php echo $this->get_field_id( 'ytrss' ); ?>"><?php _e('Youtube RSS Feed:', "solostream"); ?></label>
		<textarea rows="3" id="<?php echo $this->get_field_id( 'ytrss' ); ?>" name="<?php echo $this->get_field_name( 'ytrss' ); ?>" style="width:100%;"><?php echo $instance['ytrss']; ?></textarea></p>

	<?php
	}
}

/*-----------------------------------------------------------------------------------*/
// This starts the 300x250 Banner Ad widget.
/*-----------------------------------------------------------------------------------*/

add_action( 'widgets_init', 'banner300_load_widgets' );

function banner300_load_widgets() {
	register_widget( 'Banner300_Widget' );
}

class Banner300_Widget extends WP_Widget {

	function Banner300_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'banner300', 'description' => __('Adds 300x250 banner ad.', "solostream") );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'banner300-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'banner300-widget', __('300x250 Banner Ad Widget', "solostream"), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$adcode = $instance['adcode'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title; 

		/* Display ad code. */
		echo $adcode;

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Sponsor Ad', "solostream"));

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', "solostream"); ?></label>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>

		<!-- adcode: Text Input -->
		<p><label for="<?php echo $this->get_field_id( 'adcode' ); ?>"><?php _e('Banner Ad Code:', "solostream"); ?></label>
		<textarea rows="3" id="<?php echo $this->get_field_id( 'adcode' ); ?>" name="<?php echo $this->get_field_name( 'adcode' ); ?>" style="width:100%;"><?php echo $instance['adcode']; ?></textarea></p>

	<?php
	}
}

/*-----------------------------------------------------------------------------------*/
// This starts the Featured Pagewidget.
/*-----------------------------------------------------------------------------------*/

add_action('widgets_init', create_function('', "register_widget('Featured_Page');"));
class Featured_Page extends WP_Widget {

	function Featured_Page() {
		$widget_ops = array( 'classname' => 'featuredpage', 'description' => __('Displays a featured page with thumbnail and excerpt.', 'solostream') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'featured-page' );
		$this->WP_Widget( 'featured-page', __('Featured Page', 'solostream'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);
		
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'page_id' => '',
			'show_image' => 0,
			'image_alignment' => '',
			'image_size' => '',
			'show_title' => 0,
			'show_content' => 0,
			'content_limit' => '',
			'more_text' => ''
		));

		echo $before_widget;

		$featured_page = new WP_Query(array('page_id' => $instance['page_id']));
		if($featured_page->have_posts()) : while($featured_page->have_posts()) : $featured_page->the_post(); ?>

			<?php if (!empty($instance['show_title'])) : ?>
				<?php echo $before_title . apply_filters('widget_title', get_the_title()) . $after_title; ?>
			<?php elseif (!empty($instance['title'])) : ?> 
				<?php echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title; ?>
			<?php endif; ?>	
			
			<div class="post clearfix">

				<?php if(!empty($instance['show_image'])) : ?>
					<?php if ( function_exists('get_the_image')) {
						if (get_option('solostream_default_thumbs') == 'yes') { $defthumb = get_bloginfo('stylesheet_directory') . '/images/def-thumb.jpg'; } else { $defthumb == 'false'; }
						$solostream_img = get_the_image(array(
							'meta_key' => 'thumbnail',
							'size' => 'medium',
							'image_class' => 'thumbnail',
							'default_image' => $defthumb,
							'format' => 'array',
							'image_scan' => true,
							'link_to_post' => false, ));
						if ( $solostream_img['url'] && get_option('solostream_show_thumbs') == 'yes' && get_post_meta( $post->ID, 'remove_thumb', true ) != 'Yes' ) { ?>
							<?php if ( $instance['image_alignment'] == "" ) { ?>
								<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><img class="<?php echo $solostream_img['class']; ?>" src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $solostream_img['url']; ?>&amp;w=300&amp;h=150&amp;zc=1" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /></a>
							<?php } else { ?>
								<a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><img class="<?php echo $solostream_img['class']; ?> <?php echo $instance['image_alignment']; ?>" src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo $solostream_img['url']; ?>&amp;w=150&amp;h=150&amp;zc=1" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /></a>
							<?php } ?>					
						<?php } 
					} ?>
				<?php endif; ?>	
			
				<?php if(!empty($instance['show_content'])) : ?> 				
					<?php $excerpt = get_the_excerpt(); echo string_limit_words($excerpt,$instance['content_limit']); ?>
				<?php endif; ?>

				<?php if(!empty($instance['more_text'])) : ?>
					 <a href="<?php the_permalink() ?>" rel="<?php _e("bookmark", "solostream"); ?>" title="<?php _e("Permanent Link to", "solostream"); ?> <?php the_title(); ?>"><?php echo esc_html( $instance['more_text'] ); ?></a>
				<?php endif; ?>
				
			</div>
					
		<?php endwhile; endif;
		
		echo $after_widget;
		wp_reset_query();
	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) { 
		
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'page_id' => '',
			'show_image' => 0,
			'image_alignment' => '',
			'image_size' => '',
			'show_title' => 0,
			'show_content' => 0,
			'content_limit' => '40',
			'more_text' => ' ... Read More &raquo;'
		) );
		
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'solostream'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" /></p>
		
		<p><label for="<?php echo $this->get_field_id('page_id'); ?>"><?php _e('Page', 'solostream'); ?>:</label>
		<?php wp_dropdown_pages(array('name' => $this->get_field_name('page_id'), 'selected' => $instance['page_id'])); ?></p>
		
		<p><input id="<?php echo $this->get_field_id('show_image'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_image'); ?>" value="1" <?php checked(1, $instance['show_image']); ?>/> <label for="<?php echo $this->get_field_id('show_image'); ?>"><?php _e('Show Page Image', 'solostream'); ?></label></p>
	
		<p><label for="<?php echo $this->get_field_id('image_alignment'); ?>"><?php _e('Image Alignment', 'solostream'); ?>:</label>
		<select id="<?php echo $this->get_field_id('image_alignment'); ?>" name="<?php echo $this->get_field_name('image_alignment'); ?>">
			<option style="padding-right:10px;" value="">- <?php _e('Top', 'solostream'); ?> -</option>
			<option style="padding-right:10px;" value="alignleft" <?php selected('alignleft', $instance['image_alignment']); ?>>- <?php _e('Left', 'solostream'); ?> -</option>
			<option style="padding-right:10px;" value="alignright" <?php selected('alignright', $instance['image_alignment']); ?>>- <?php _e('Right', 'solostream'); ?> -</option>
		</select></p>		

		<p><input id="<?php echo $this->get_field_id('show_title'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php checked(1, $instance['show_title']); ?>/> <label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show Page Title in Place of Widget Title', 'solostream'); ?></label></p>
			
		<p><input id="<?php echo $this->get_field_id('show_content'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_content'); ?>" value="1" <?php checked(1, $instance['show_content']); ?>/> <label for="<?php echo $this->get_field_id('show_content'); ?>"><?php _e('Show Page Content', 'solostream'); ?></label></p>
		
		<p><label for="<?php echo $this->get_field_id('content_limit'); ?>"><?php _e('Word Limit', 'solostream'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('content_limit'); ?>" name="<?php echo $this->get_field_name('content_limit'); ?>" value="<?php echo esc_attr( $instance['content_limit'] ); ?>" size="3" /></p>
		
		<p><label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e('Read More Text', 'solostream'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" value="<?php echo esc_attr( $instance['more_text'] ); ?>" /></p>
			
	<?php 
	}
}

// <!--Shalinga -->


add_action( 'widgets_init', 'recent_event' );

function recent_event() {
	register_widget( 'recent_event' );
}

/**
 * Recent_Posts widget class
 *
 * @since 2.8.0
 */
class recent_event extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent event on your site") );
		parent::__construct('recent-event', __('Recent Event'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_event', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Event') : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('category_name'=>'events','posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_events', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

	<?php 
	}
}

add_action( 'widgets_init', 'recent_news' );

function recent_news() {
	register_widget( 'recent_news' );
}

/**
 * Recent_News widget class
 *
 * @since 2.8.0
 */
class recent_news extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent news on your site") );
		parent::__construct('recent-news', __('Recent News'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_news', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent News') : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('category_name'=>'news','posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_events', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

	<?php 
	}
}


add_action( 'widgets_init', 'recent_notnews' );

function recent_notnews() {
	register_widget( 'recent_notnews' );
}

/**
 * Recent_Posts With out News widget class
 *
 * @since 2.8.0
 */
class recent_notnews extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent notnews on your site") );
		parent::__construct('recent-notnews', __('Recent notnews'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_notnews', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent notnews') : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('category__not_in'=> array(31),'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post();?>
		<li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_notnews', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

<!--Shalinga-->
	<?php 
	}
}


add_action( 'widgets_init', 'msl_product' );

function msl_product() {
	register_widget( 'msl_product' );
}

/**
 * MSL_PRODUCTS widget class
 *
 * @since 2.8.0
 */
class msl_product extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent news on your site") );
		parent::__construct('msl-products', __('MSL Products'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		
		$post_id = $GLOBALS['post']->ID;
	
		$cache = wp_cache_get('widget_recent_event', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('MSL PRODUCTS') : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('post_type'=> 'bc_msl','posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :
		//var_dump($r);
		
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>

		<?php  while ($r->have_posts()) : $r->the_post(); 
		echo "<div class='pro_main'>";
			echo "<div class='pro_title'>";?>			
		<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
		<?php
			echo "</div>";
		$post_id = get_the_ID();
		
		$thumbid = get_post_thumbnail_id($post_id );
		$featuredimage = wp_get_attachment_image_src($thumbid, 'thumbnail');
	  $featuredimage = $featuredimage[0];
		if ($featuredimage){
			$img = "<img src = '$featuredimage' width ='75' height = '75'/>";
		} else{
			//$img = "<img src = 'wp-content/themes/shalinga/images/no_profile_pic.jpg' width ='108' height = '144'/>";
		}
		
		
		$price = get_post_meta($post_id,'bc_price', true);
		$link = get_post_meta($post_id,'bc_link', true);
		echo $img;
		the_content(); 
		echo $price;
		echo "<p>";
if ($link){
		echo "<a href='http://$link' target='_blank'><input type='button' class="msl_buy_button new_msl_buy_button" name='Bye Product' value='Buy Now'></a>";
}
		
		echo "</div>";
		echo "<br>";?>
		
<?php endwhile; ?>

		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_events', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

<!--Shalinga-->
	<?php 
	}
}
