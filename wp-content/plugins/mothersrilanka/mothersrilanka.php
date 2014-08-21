<?php
/*
Plugin Name: Mother Sri Lanka
Plugin URI: http://www.mothersrilanka.lk
Description: Mother Sri Lanka
Version: 1.0
Author: Venturit
Author URI: http://www.venturit.com
License:GPL2
*/
  

global $prefix;
$prefix = "bc_";

add_action('init', 'bc_msl_product');

function bc_msl_product() {
	global $prefix;
	register_post_type( $prefix.'msl',
		array(
			'labels' => array(
			'name' => _('MSL Patriot Collection'),
			'singular_name' => _('MSL Patriot Collection'),
			'add_new' => _('Add Product'),
			'search_items' => _('Search Product'),
			'all_items' => _('All Product'),
			'edit_item' => _('Edit Product'),
			'update_item' => _('Update Product'),
			'add_new_item' => _('Add Product'),
			'new_item_name' => _('New Product'),
			'not_found' => _('No Product found'),
			'not_found_in_trash' => _('No Product found in Trash'),
		    ),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'msl'),
		'taxonomies'=> array('post_tag','msl'),
		'supports'=>array('title','editor','author','thumbnail','revisions'),
		'register_meta_box_cb' => 'bc_msl_meta_boxes', 

		  
		 )
	  );
}
// register meta box
$meta_box = array(
		'id'=> 'msl-meta-box',
		'title' => 'Other notes',
		'callback' => 'bc_msl_show_box',
		'page' => $prefix.'msl',
		'context' => 'normal',
		'priority' => 'high',
	    'fields' =>  array( array(
			'name' => 'Link',
			'desc' => '',
			'id' => $prefix . 'link',
			'type' => 'text',
			'std' => '' //
		),
			array(
				'name' => 'Price',
				'desc' => '',
				'id' => $prefix . 'price',
				'type' => 'text',
				'std' => '' //
			)  
     )
);


function bc_msl_meta_boxes() {

global $meta_box;
global $prefix;
	$meta_boxes = array($meta_box);
	foreach ($meta_boxes as $mb) {
			add_meta_box($mb['id'], $mb['title'], $mb['callback'], $mb['page'], $mb['context'], $mb['priority']);	
	}
}


// Callback function to show fields in meta box
function bc_msl_show_box() {
  
	global $meta_box, $post, $prefix;

	// Use nonce for verification
	echo '<input type="hidden" name="'.$prefix.'msl_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	

	foreach ($meta_box['fields'] as $field) {

		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		
		switch ($field['type']) {
			case 'text':
				echo "<br /><div> <span>".$field['name']."</span><br />";
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
					'<br />', $field['desc'];
				break;
			case 'textarea':
				echo "<br /><div> <span>".$field['name']."</span><br />";
				echo '<textarea name="', $field['id'], '" id="', $field['id'], '" class="theEditor" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
					'<br />';
				break;
			case 'select':
				echo "<br /><div> <span>".$field['name']."</span><br />";
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>';
				break;
			case 'radio':
				echo "<br /><div> <span>".$field['name']."</span><br />";
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
				}
				break;
			case 'checkbox':
				echo "<br /><div> <span>".$field['name']."</span><br />";
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
				break;
			
			case 'hidden':
					echo "<br /><div>";
					echo '<input type="hidden" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" />';
					break;
		}
		echo "</div><br />";
	}
}

add_action('save_post', 'bc_msl_save_meta_data');

// Save data from meta boxes
function bc_msl_save_meta_data($post_id) {
global $meta_box, $prefix;

// verify nonce
if (!wp_verify_nonce($_POST[$prefix.'msl_meta_box_nonce'], basename(__FILE__))) {
return $post_id;
}

// check autosave
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
return $post_id;
}

// check permissions
if ('page' == $_POST['post_type']) {
if (!current_user_can('edit_page', $post_id)) {
return $post_id;
}
} elseif (!current_user_can('edit_post', $post_id)) {
return $post_id;
}

foreach ($meta_box['fields'] as $field) {
$old = get_post_meta($post_id, $field['id'], true);
$new = $_POST[$field['id']];

if ($new && $new != $old) {
update_post_meta($post_id, $field['id'], $new);
} elseif ('' == $new && $old) {
delete_post_meta($post_id, $field['id'], $old);
}
}

if (wp_is_post_revision($post_id)) {
return;
}

}

function bc_admin_head() {
  echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('wp-admin.css', __FILE__). '">';
}
add_action('admin_head', 'bc_admin_head');

?>
