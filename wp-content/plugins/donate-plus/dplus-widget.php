<?php

function widget_donateplus_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_donateplusform($args) {
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_donateplusform');
		$title = $options['title'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		echo $before_widget . $before_title . $title . $after_title;
		DonatePlusForm();
		echo $after_widget;
	}
	function widget_donateplustotal($args) {
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_donateplustotal');
		$title = $options['title'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		echo $before_widget . $before_title . $title . $after_title;
		DonatePlusTotal();
		echo $after_widget;
	}
	function widget_donatepluswall($args) {
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_donatepluswall');
		$title = $options['title'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		echo $before_widget . $before_title . $title . $after_title;
		DonatePlusWall();
		echo $after_widget;
	}


	function widget_donateplusform_control() {
		$options = get_option('widget_donateplusform');
		if ( !is_array($options) )
			$options = array('title'=>'Donate');
		if ( $_POST['donateplusf-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['donateplusf-title']));
			update_option('widget_donateplusform', $options);
		}
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		echo '<p style="text-align:right;"><label for="donateplusf-title">' . __('Title:') . ' <input style="width: 200px;" id="donateplusf-title" name="donateplusf-title" type="text" value="'.$title.'" /></label></p>';
		echo '<input type="hidden" id="donateplusf-submit" name="donateplusf-submit" value="1" />';
	}
	function widget_donateplustotal_control() {
		$options = get_option('widget_donateplustotal');
		if ( !is_array($options) )
			$options = array('title'=>'Total Donations');
		if ( $_POST['donateplust-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['donateplust-title']));
			update_option('widget_donateplustotal', $options);
		}
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		echo '<p style="text-align:right;"><label for="donateplust-title">' . __('Title:') . ' <input style="width: 200px;" id="donateplust-title" name="donateplust-title" type="text" value="'.$title.'" /></label></p>';
		echo '<input type="hidden" id="donateplust-submit" name="donateplust-submit" value="1" />';
	}
	function widget_donatepluswall_control() {
		$options = get_option('widget_donatepluswall');
		if ( !is_array($options) )
			$options = array('title'=>'Recognition Wall');
		if ( $_POST['donateplusw-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['donateplusw-title']));
			update_option('widget_donatepluswall', $options);
		}
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		echo '<p style="text-align:right;"><label for="donateplusw-title">' . __('Title:') . ' <input style="width: 200px;" id="donateplusw-title" name="donateplusw-title" type="text" value="'.$title.'" /></label></p>';
		echo '<input type="hidden" id="donateplusw-submit" name="donateplusw-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Donate Plus Form', 'widgets'), 'widget_donateplusform');
	register_sidebar_widget(array('Donate Plus Total', 'widgets'), 'widget_donateplustotal');
	register_sidebar_widget(array('Donate Plus Wall', 'widgets'), 'widget_donatepluswall');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Donate Plus Form', 'widgets'), 'widget_donateplusform_control', 300, 100);
	register_widget_control(array('Donate Plus Total', 'widgets'), 'widget_donateplustotal_control', 300, 100);
	register_widget_control(array('Donate Plus Wall', 'widgets'), 'widget_donatepluswall_control', 300, 100);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_donateplus_init');
?>