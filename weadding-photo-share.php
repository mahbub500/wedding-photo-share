<?php
/**
 * Plugin Name: Weading Photo Share
 * Description: Just another plugin by Codexpert
 * Plugin URI: https://codexpert.io
 * Author: Codexpert, Inc
 * Author URI: https://codexpert.io
 * Version: 0.9
 * Text Domain: weadding-photo-share
 * Domain Path: /languages
 */

namespace Wppluginhub\Weading_Photo_Share;
use WpPluginHub\Plugin\Notice;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author Codexpert <hi@codexpert.io>
 */
final class Plugin {
	
	/**
	 * Plugin instance
	 * 
	 * @access private
	 * 
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * The constructor method
	 * 
	 * @access private
	 * 
	 * @since 0.9
	 */
	private function __construct() {
		/**
		 * Includes required files
		 */
		$this->include();

		/**
		 * Defines contants
		 */
		$this->define();

		/**
		 * Runs actual hooks
		 */
		$this->hook();
	}

	/**
	 * Includes files
	 * 
	 * @access private
	 * 
	 * @uses composer
	 * @uses psr-4
	 */
	private function include() {
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	}

	/**
	 * Define variables and constants
	 * 
	 * @access private
	 * 
	 * @uses get_plugin_data
	 * @uses plugin_basename
	 */
	private function define() {

		/**
		 * Define some constants
		 * 
		 * @since 0.9
		 */
		define( 'WEADDING_PHOTO_SHARE', __FILE__ );
		define( 'WEADDING_PHOTO_SHARE_DIR', dirname( WEADDING_PHOTO_SHARE ) );
		define( 'WEADDING_PHOTO_SHARE_ASSET', plugins_url( 'assets', WEADDING_PHOTO_SHARE ) );
		define( 'WEADDING_PHOTO_SHARE_DEBUG', apply_filters( 'weadding-photo-share_debug', true ) );

		/**
		 * The plugin data
		 * 
		 * @since 0.9
		 * @var $plugin
		 */
		$this->plugin					= get_plugin_data( WEADDING_PHOTO_SHARE );
		$this->plugin['basename']		= plugin_basename( WEADDING_PHOTO_SHARE );
		$this->plugin['file']			= WEADDING_PHOTO_SHARE;
		$this->plugin['server']			= apply_filters( 'weadding-photo-share_server', 'https://codexpert.io/dashboard' );
		$this->plugin['min_php']		= '5.6';
		$this->plugin['min_wp']			= '4.0';
		$this->plugin['icon']			= WEADDING_PHOTO_SHARE_ASSET . '/img/icon.png';
		// $this->plugin['depends']		= [ 'woocommerce/woocommerce.php' => 'WooCommerce' ];
		
	}

	/**
	 * Hooks
	 * 
	 * @access private
	 * 
	 * Executes main plugin features
	 *
	 * To add an action, use $instance->action()
	 * To apply a filter, use $instance->filter()
	 * To register a shortcode, use $instance->register()
	 * To add a hook for logged in users, use $instance->priv()
	 * To add a hook for non-logged in users, use $instance->nopriv()
	 * 
	 * @return void
	 */
	private function hook() {

		if( is_admin() ) :

			/**
			 * Admin facing hooks
			 */
			$admin = new App\Admin( $this->plugin );
			$admin->activate( 'install' );
			$admin->action( 'admin_footer', 'modal' );
			$admin->action( 'plugins_loaded', 'i18n' );
			$admin->action( 'admin_enqueue_scripts', 'enqueue_scripts' );
			$admin->action( 'admin_footer_text', 'footer_text' );

			/**
			 * Settings related hooks
			 */
			$settings = new App\Settings( $this->plugin );
			$settings->action( 'plugins_loaded', 'init_menu' );


		else : // ! is_admin() ?

			/**
			 * Front facing hooks
			 */
			$front = new App\Front( $this->plugin );
			$front->action( 'wp_head', 'head' );
			$front->action( 'wp_footer', 'modal' );
			$front->action( 'wp_enqueue_scripts', 'enqueue_scripts' );

			/**
			 * Shortcode related hooks
			 */
			$shortcode = new App\Shortcode( $this->plugin );
			$shortcode->register( 'weading_qr', 'qr_code_shortcode' );
			$shortcode->register( 'image_uploader', 'image_upload_shortcode' );
			

		endif;

		/**
		 * AJAX related hooks
		 */
		$ajax = new App\AJAX( $this->plugin );
		$ajax->all( 'image_upload','image_upload_handler' );
		$ajax->all( 'get_images','live_images_handler' );
	}

	/**
	 * Cloning is forbidden.
	 * 
	 * @access public
	 */
	public function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 * 
	 * @access public
	 */
	public function __wakeup() { }

	/**
	 * Instantiate the plugin
	 * 
	 * @access public
	 * 
	 * @return $_instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin::instance();