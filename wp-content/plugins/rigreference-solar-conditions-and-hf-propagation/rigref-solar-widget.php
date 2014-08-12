<?php 
/**
 * Plugin Name: RigReference HF-Propagation
 * Plugin URI: http://www.rigreference.com/solar/
 * Description: Solar and HF-propagation conditions based on NOAA data. 
 * Version: 1.0.0
 * Author: RigReference.com
 * Author URI: http://www.rigreference.com
 */

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * Actions and Filters
 * 
 * Register any and all actions here. Nothing should actually be called 
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', create_function( '', 'register_widget("RigReference_HF_Propagation");' ) );


/**
 * 
 * @author byrd
 * Document Widget
 */
class RigReference_HF_Propagation extends WP_Widget
{
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	function RigReference_HF_Propagation()
	{
		// widget actual processes
		parent::WP_Widget( $id = 'RigReference_HF_Propagation', $name = get_class($this), $options = array( 'description' => 'Solar and HF-propagation conditions' ) );
	}

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array( 'format' => '' ) );
		$format = strip_tags($instance['format']);
		?>
		
		Format: 
		<select name="<?=$this->get_field_name('format')?>" id="<?=$this->get_field_id('format')?>">
			<option value="tall" <?=($format=='tall'?'selected="selected"':'')?>>Tall</option>
			<option value="wide" <?=($format=='wide'?'selected="selected"':'')?>>Wide</option>
		</select>
		<br/><span class="description">Please select how to display the widget: Tall (160×335 pixels) or Wide (320×100 pixels).</span>

		<?php 
	}

	function update($new_instance, $old_instance)
	{
		// processes widget options to be saved
		$instance = wp_parse_args($old_instance, $new_instance);
		$instance['format'] = strip_tags($new_instance['format']);
		return $instance;
	}

	function widget($args, $instance)
	{
		// outputs the content of the widget
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$format = apply_filters( 'widget_format', $instance['format'] );

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
		
<div id="rigref-solar-widget"></div>
<script type="text/javascript">
(function() {
	var a = document.createElement('a');
	var i = document.createElement('img');
	var s = document.getElementById('rigref-solar-widget');
	a.href = ('http://rigreference.com/solar');
	a.target = '_blank';
	i.src = ('http://rigreference.com/solar/latest/<?=$format?>');
	i.border = 0;
	a.appendChild(i);s.appendChild(a);
})();
</script>
		
		<?php 
	}

}