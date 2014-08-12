<?php
/**
 * Plugin Name: QRZ.com Search Widget
 * Plugin URI: http://wordpress.eagleflint.com/plugins/qrzsearch.php
 * Description: A widget that populates a Ham Call Sign search form to the QRZ.com website
 * Version: 0.1.6.2
 * Date: 2012-08-28
 * Author: Flint Gatrell, N0FHG
 * Author URI: http://blog.eagleflint.com
 * Copyright: 2011 by Flint Gatrell
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'qrzsearch_load_widgets' );

/**
 * Register our widget.
 * 'QRZSearch_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function qrzsearch_load_widgets() {
	register_widget( 'QRZSearch_Widget' );
}

/**
 * QRZ Search Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class QRZSearch_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function QRZSearch_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'qrzsearch', 'description' => __('A simple widget that displays a Ham Call Sign search form against the QRZ.com website.', 'qrzsearch') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'qrzsearch-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'qrzsearch-widget', __('QRZ Search Widget', 'qrzsearch'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$qrzsearchtitle = apply_filters('widget_title', $instance['qrzsearchtitle'] );
		$qrzsearchbuttontext = $instance['qrzsearchbuttontext'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $qrzsearchtitle )
			echo $before_title . $qrzsearchtitle . $after_title;

		/* Display search control from widget settings if button text is defined. */
		if ( $qrzsearchbuttontext )
			echo ('<script type="text/javascript">
				<!--
				function qrzsearchpop(myform, windowname)
				{
				if (! window.focus)return true;
				window.open(\'\', windowname, \'height=800,width=1000,scrollbars=yes\');
				myform.target=windowname;
				return true;
				}
				//-->
				</script>');
			printf( '<div><form method="get" action="http://www.qrz.com/db/" onSubmit="qrzsearchpop(this, \'QRZ\')">
				<input type="text" name="callsign" value="W0DTF" />
				<input type="submit" value="'.__('%1$s', 'qrzsearch').'" /></form></div>', $qrzsearchbuttontext );

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['qrzsearchtitle'] = strip_tags( $new_instance['qrzsearchtitle'] );
		$instance['qrzsearchbuttontext'] = strip_tags( $new_instance['qrzsearchbuttontext'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'qrzsearchtitle' => __('QRZ Call Sign Lookup', 'qrzsearch'), 'qrzsearchbuttontext' => __('Search', 'qrzsearch'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'qrzsearchtitle' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'qrzsearchtitle' ); ?>" name="<?php echo $this->get_field_name( 'qrzsearchtitle' ); ?>" value="<?php echo $instance['qrzsearchtitle']; ?>" style="width:100%;" />
		</p>

		<!-- Search Button Text: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'qrzsearchbuttontext' ); ?>"><?php _e('Search Button Text:', 'qrzsearch'); ?></label>
			<input id="<?php echo $this->get_field_id( 'qrzsearchbuttontext' ); ?>" name="<?php echo $this->get_field_name( 'qrzsearchbuttontext' ); ?>" value="<?php echo $instance['qrzsearchbuttontext']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}

?>