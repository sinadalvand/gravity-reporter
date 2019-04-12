<?php


class wp_get_params {
	public static function display_get_param($atts) {
		if (!isset($atts['name'])) {
			return '<b>display-get-param requires a name attribute</b>';
		}
		$name = $atts['name'];
		$default = isset($atts['default']) ? $atts['default'] : '<blank value>';

        $val = (int) isset($_GET[$name]) ? $_GET[$name] : $default;

		return do_shortcode(" [gravityform id='{$val}' title='true' description='true']");
	}
}

add_shortcode( 'gform', Array('wp_get_params', 'display_get_param') );

