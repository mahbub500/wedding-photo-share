<?php
/**
 * All public facing functions
 */
namespace Wppluginhub\Weading_Photo_Share\App;
use WpPluginHub\Plugin\Base;
use Wppluginhub\Weading_Photo_Share\Helper;


/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Front
 * @author Codexpert <hi@codexpert.io>
 */
class Front extends Base {

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

	public function head() {
		



	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'WEADDING_PHOTO_SHARE_DEBUG' ) && WEADDING_PHOTO_SHARE_DEBUG ? '' : '.min';

		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/front{$min}.css", WEADDING_PHOTO_SHARE ), '', $this->version, 'all' );

		wp_enqueue_style( $this->slug . '-pagination', 'https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.css', '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/front{$min}.js", WEADDING_PHOTO_SHARE ), [ 'jquery' ], $this->version, true );
		wp_enqueue_script( $this->slug . '-pagination', 'https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.min.js', [ 'jquery' ], $this->version, true );
		
		$localized = [
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'_wpnonce'	=> wp_create_nonce( $this->slug ),
		];
		wp_localize_script( $this->slug, 'WEADDING_PHOTO_SHARE', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function modal() {
		echo '
		<div id="weadding-photo-share-modal" style="display: none">
			<img id="weadding-photo-share-modal-loader" src="' . esc_attr( WEADDING_PHOTO_SHARE_ASSET . '/img/loader.gif' ) . '" />
		</div>';
	}


}