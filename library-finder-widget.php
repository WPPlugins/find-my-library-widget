<?php
/*
	Plugin Name: Find My Library widget
	Plugin URI: http://www.findmylibrary.co.uk/wordpresswidget
	Description: Easily add a library finder as a widget to your site.
	Version: 1.0.0
	Author: Find My Library
	Author URI: http://www.findmylibrary.co.uk
	License: GPLv2
    Copyright (C) 2014 Dave Rowe / Find My Library

    This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class library_finder_widget extends WP_Widget {

	// constructor
	function library_finder_widget() {
		parent::WP_Widget(false, $name = __('Find My Library', 'wp_widget_plugin'), array( 'description' => __( 'Displays a library finder to search libraries by address', 'wp_widget_plugin' )) ); 
	}

	// widget form creation
	function form($instance) {

		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 $textarea = esc_textarea($instance['textarea']);
			 $checkbox1 = esc_attr($instance['checkbox1']);
			 $checkbox2 = esc_attr($instance['checkbox2']);
		} else {
			 $title = '';
			 $text = '';
			 $textarea = '';
			 $checkbox1 = '';
			 $checkbox2 = '';
		}
	?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Textarea:', 'wp_widget_plugin'); ?></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo $textarea; ?></textarea>
		</p>

		<p>
		<input id="<?php echo $this->get_field_id('checkbox1'); ?>" name="<?php echo $this->get_field_name('checkbox1'); ?>" type="checkbox" value="1" <?php checked( '1', $checkbox1 ); ?> /> 
		<label for="<?php echo $this->get_field_id('checkbox1'); ?>">Public libraries</label> 
		</p>
		
		<p>
		<input id="<?php echo $this->get_field_id('checkbox2'); ?>" name="<?php echo $this->get_field_name('checkbox2'); ?>" type="checkbox" value="1" <?php checked( '1', $checkbox2 ); ?> />
		<label for="<?php echo $this->get_field_id('checkbox2'); ?>">Non-public libraries</label>
		</p>

	<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['textarea'] = strip_tags($new_instance['textarea']);
		$instance['checkbox1'] = strip_tags($new_instance['checkbox1']);
		$instance['checkbox2'] = strip_tags($new_instance['checkbox2']);
		return $instance;
	}

	// widget display
	function widget($args, $instance) {
		wp_enqueue_script( 'google_geocode', 'http://maps.google.com/maps/api/js?sensor=false' );
		wp_enqueue_script( 'library_finder', 'http://www.findmylibrary.co.uk/scripts/library-finder-widget.js' );

		extract( $args );
		// these are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		$textarea = $instance['textarea'];
		$checkbox1 = $instance['checkbox1'];
		$checkbox2 = $instance['checkbox2'];
		
		echo $before_widget;
		
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';

		// Check if title is set
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		// Check if textarea is set
		if( $textarea ) {
			echo '<p class="wp_widget_plugin_textarea">'.$textarea.'</p>';
		}
		
		$publicStr = ($checkbox1) ? 'true' : 'false';
		$nonPublicStr = ($checkbox2) ? 'true' : 'false';

		echo '<label for="txbLocation" class="screen-reader-text">Enter location </label>';
		echo '<input id="txbLocation" name="txtLocation" class="field" type="text" /><br /><br />';
		echo '<input type="button" onclick="loadLibraries(';
		echo $nonPublicStr;
		echo ',';
		echo $publicStr;
		echo ')" class="submit" value="Find libraries" /><br />';
		echo '<div id="divLibraries"></div>';
		echo '</div>';
		echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("library_finder_widget");'));
?>