<?php
/**
 * Created by PhpStorm.
 * User: LOGAN
 * Date: 9/10/2018
 * Time: 11:50 AM
 */

/*
Plugin Name: گزارشگر گراویتی فرم
Plugin URI: www.sinadalvand.ir
Description:   افرونه گزارشگر گراویتی فرم جهت سهولت در ارسال اطلاعات فرم به سیستم های سوم شخص به صورت خصوصی کد نویسی شده و حقوق آن محفوظ است.
Author: Sina Dalvand
Version: 1.0.0
Author URI: www.sinadalvand.ir
*/



defined( "ABSPATH" ) || exit();

class Gform_Reporter {

	protected static $_instance;
	protected $_version = "1.0.0";


	public static function getinstance() {
		if ( is_null( Self::$_instance ) ) {
			Self::$_instance = new self;
		}
	}


	public function __construct() {
		$this->definer();
		$this->includer();
	}


	private function definer() {
		define( "GREPORTER_DIR", plugin_dir_path( __FILE__ ) );
		define( "GREPORTER_URL", plugin_dir_url( __FILE__ ) );
	}

	private function includer() {
        require GREPORTER_DIR.DIRECTORY_SEPARATOR.'wp_get_params.php';
        require GREPORTER_DIR.DIRECTORY_SEPARATOR.'Gform_apply.php';
        require GREPORTER_DIR.DIRECTORY_SEPARATOR.'GF_Field_Kind.php';
	}




}
Gform_Reporter::getinstance();
$form = new Gform_apply();


