<?php
/**
 * All settings related functions
 */
namespace Wppluginhub\Weading_Photo_Share\App;
use Wppluginhub\Weading_Photo_Share\Helper;
use WpPluginHub\Plugin\Base;
use WpPluginHub\Plugin\Settings as Settings_API;

/**
 * @package Plugin
 * @subpackage Settings
 * @author Codexpert <hi@codexpert.io>
 */
class Settings extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}
	
	public function init_menu() {
		
		$site_config = [
			'PHP Version'				=> PHP_VERSION,
			'WordPress Version' 		=> get_bloginfo( 'version' ),
			'WooCommerce Version'		=> is_plugin_active( 'woocommerce/woocommerce.php' ) ? get_option( 'woocommerce_version' ) : 'Not Active',
			'Memory Limit'				=> defined( 'WP_MEMORY_LIMIT' ) && WP_MEMORY_LIMIT ? WP_MEMORY_LIMIT : 'Not Defined',
			'Debug Mode'				=> defined( 'WP_DEBUG' ) && WP_DEBUG ? 'Enabled' : 'Disabled',
			'Active Plugins'			=> get_option( 'active_plugins' ),
		];

		$settings = [
			'id'            => $this->slug,
			'label'         => $this->name,
			'title'         => "{$this->name} v{$this->version}",
			'header'        => $this->name,
			// 'parent'     => 'woocommerce',
			// 'priority'   => 10,
			// 'capability' => 'manage_options',
			// 'icon'       => 'dashicons-wordpress',
			// 'position'   => 25,
			// 'topnav'	=> true,
			'sections'      => [
				'weadding-photo-share_basic'	=> [
					'id'        => 'weadding-photo-share_basic',
					'label'     => __( 'Basic Settings', 'weadding-photo-share' ),
					'icon'      => 'dashicons-admin-tools',
					// 'color'		=> '#4c3f93',
					'sticky'	=> false,
					'fields'    => [
						'image_page' => [
							'id'      => 'image_page',
							'label'     => __( 'Select Image uplode page', 'weadding-photo-share' ),
							'type'      => 'select',
							'desc'      => __( 'jQuery Chosen plugin enabled. <a href="https://harvesthq.github.io/chosen/" target="_blank">[See more]</a>', 'weadding-photo-share' ),
							// 'class'     => '',
							'options'   => Helper::get_posts( [ 'post_type' => 'page' ], false, true ),
							'default'   => 2,
							'disabled'  => false, // true|false
							'multiple'  => false, // true|false
							'chosen'    => true
						]
						
					]
				],
				
				'weadding-photo-share_tools'	=> [
					'id'        => 'weadding-photo-share_tools',
					'label'     => __( 'Tools', 'weadding-photo-share' ),
					'icon'      => 'dashicons-hammer',
					'sticky'	=> false,
					'fields'    => [
						'enable_debug' => [
							'id'      	=> 'enable_debug',
							'label'     => __( 'Enable Debug', 'weadding-photo-share' ),
							'type'      => 'switch',
							'desc'      => __( 'Enable this if you face any CSS or JS related issues.', 'weadding-photo-share' ),
							'disabled'  => false,
						],
						'report' => [
							'id'      => 'report',
							'label'     => __( 'Report', 'weadding-photo-share' ),
							'type'      => 'textarea',
							'desc'     	=> '<button id="weadding-photo-share_report-copy" class="button button-primary"><span class="dashicons dashicons-admin-page"></span></button>',
							'columns'   => 24,
							'rows'      => 10,
							'default'   => json_encode( $site_config, JSON_PRETTY_PRINT ),
							'readonly'  => true,
						],
					]
				]
			],
		];

		new Settings_API( $settings );
	}
}