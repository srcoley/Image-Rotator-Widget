<?php
	/**
	Plugin Name: DK New Media's Image Rotator Widget
	Plugin URI: http://www.dknewmedia.com
	Description: A sidebar widget for rotating images utilizing jQuery. Built by <a href="http://dknewmedia.com">DK New Media</a>.
	Version: 0.1.3
	Author: Stephen Coley, Douglas Karr
	Author URI: http://www.dknewmedia.com

	Copyright 2011  DK New Media  (email : doug@dknewmedia.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/


	
	/**
	 * Script & stype loader for widget.php
	 */
	function irw_admin_actions($hook) {
		if('widgets.php' != $hook) {
			return;
		}
		// Scripts
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('jquery-ui-sortable');
		wp_register_script('irw-js', path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) ).'/js/main.js'), array('jquery','media-upload','thickbox'));
		wp_enqueue_script('irw-js');
		wp_register_script('irw-qtip', path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) ).'/js/jquery.qtip.js'), array('jquery','media-upload','thickbox'));
		wp_enqueue_script('irw-qtip');

		// Styles
		wp_enqueue_style('thickbox');
		wp_register_style('irw-css', path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) ).'/css/main.css'));
		wp_enqueue_style('irw-css');
	}

	/**
	 * Script & style loader for the actual widget
	 */
	function irw_widget_actions() {

		// Scripts
		wp_enqueue_script('jquery'); 
		wp_register_script('jquery-imagesloaded', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)).'/js/jquery.imagesloaded.js'));
		wp_enqueue_script('jquery-imagesloaded');
		wp_register_script('irw-widget', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)).'/js/dk-image-rotator-widget.js'));
		wp_enqueue_script('irw-widget');

		// Styles
		wp_register_style('irw-widget', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)).'/css/dk-image-rotator-widget.css'));
		wp_enqueue_style('irw-widget');
	}

	/**
	 * Hooks & schizzz
	 */
	add_action('admin_enqueue_scripts', 'irw_admin_actions');
	add_action('wp_enqueue_scripts', 'irw_widget_actions');
	add_action('widgets_init', create_function('', 'register_widget("DK_Image_Rotator_Widget");'));

	/**
	 * DK_Image-Rotator-Widget Class
	 */
	class DK_Image_Rotator_Widget extends WP_Widget {

		function DK_Image_Rotator_Widget() {
			$this->WP_Widget('dk-image-rotator-widget', 'Image Rotator Widget', array('description' => 'A widgetized, bare bones image rotator.'));
		}

		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
			$image_list = $instance['irw_images'];

			// If $image_list is set and not empty...
			if(isset($image_list) && $image_list != "") {
				$images = explode(", ", $image_list);
				$transition = $instance['irw_transition'];
				echo $before_widget;
				echo '<div class="irw-widget">';
				echo '<input type="hidden" class="irw-transition" value="' . $transition . '" />';
				echo '<ul class="irw-slider">';
				// Loop through images
				foreach($images as $image) {
					echo '<li><img src="' . $image . '" /></li>';
				}
				echo '</ul></div>';
				echo $after_widget;
			// Else
			} else {
				echo '<p>You must add an image in the Widget Settings.</p>';
			}
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['irw_images'] = strip_tags($new_instance['irw_images']);
			$instance['irw_transition'] = strip_tags($new_instance['irw_transition']);
			return $instance;
		}

		function form($instance) {

			if ($instance) {
				$irw_images = esc_attr($instance['irw_images']);
				$irw_transition = esc_attr($instance['irw_transition']);
			} ?>

			<h5 class="irw_header">Options</h5>

			<p>
				<label for="<?php echo $this->get_field_name('irw_transition'); ?>">Transition: </label>
				<select class="widefat" name="<?php echo $this->get_field_name('irw_transition'); ?>" id="<?php echo $this->get_field_id('irw_transition'); ?>">
					<option <?php if($irw_transition == "linear") { echo 'selected="selected"'; } ?> value="linear">Linear</option>
					<option <?php if($irw_transition == "loop") { echo 'selected="selected"'; } ?> value="loop">Loop</option>
					<option <?php if($irw_transition == "fade") { echo 'selected="selected"'; } ?> value="fade">Fade</option>
				</select>
			</p>

			<h5 class="irw_header">Images</h5>
			
			<ul class="irw_images">
				<?php // If there are images, loop through them and echo out a list item for each one ?>
				<?php if(isset($irw_images) && $irw_images != "") : ?>
					<?php $images = explode(", ", $irw_images); // str to array ?>
					<?php $i = 1; // counter ?>
					<?php foreach($images as $image) : // Loop through images ?>
						<li data-url="<?php echo $image; ?>" ><span><?php $arr = explode("/", $image); $i = count($arr); echo $arr[$i - 1]; ?></span> <button class="button irw_button"> - </button></li>
					<?php endforeach; ?>
				<?php // Else ?>
				<?php else : ?>
					<?php $images = array(); ?>
				<?php endif; ?>
			</ul>
			<p style="width: 226px;" class="add_image text_align_right <?php if(count($images) < 1) { echo "alert"; } ?>">
				<button class="button add-image-button irw_button" onclick="media_dialog(); return false;">+</button>
			</p>
			<input type="hidden" id="<?php echo $this->get_field_id('irw_images'); ?>" class="image_list" name="<?php echo $this->get_field_name('irw_images'); ?>" value="<?php echo $irw_images; ?>" />

			<script type="text/javascript">
				jQuery(function(){
					irw_load();
				});
			</script>

		<?php }

	} ?>
