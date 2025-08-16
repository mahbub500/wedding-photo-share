<?php
/**
 * All AJAX related functions
 */
namespace Wppluginhub\Weading_Photo_Share\App;
use WpPluginHub\Plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage AJAX
 * @author Codexpert <hi@codexpert.io>
 */
class AJAX extends Base {

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

	public function image_upload_handler() {
		$response = array(
			'status'  => 0,
			'message' => __( 'Unauthorized!', 'codesigner' ),
		);

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], $this->slug ) ) {
			wp_send_json( $response );
		}

	   if (!function_exists('wp_handle_upload')) {
	        require_once(ABSPATH . 'wp-admin/includes/file.php');
	    }

	    $files = $_FILES['file'];
	    $uploaded = [];

	    // Create custom folder
	    $upload_dir = wp_upload_dir();
	    $custom_dir = $upload_dir['basedir'] . '/weadding-photos';
	    if (!file_exists($custom_dir)) {
	        wp_mkdir_p($custom_dir);
	    }

	    if (is_array($files['name'])) {
	        foreach ($files['name'] as $key => $value) {
	            if ($files['name'][$key]) {
	                $file = [
	                    'name'     => $files['name'][$key],
	                    'type'     => $files['type'][$key],
	                    'tmp_name' => $files['tmp_name'][$key],
	                    'error'    => $files['error'][$key],
	                    'size'     => $files['size'][$key]
	                ];

	                // Save file into custom folder
	                $filename = wp_unique_filename($custom_dir, $file['name']);
	                $new_path = $custom_dir . '/' . $filename;
	                if (move_uploaded_file($file['tmp_name'], $new_path)) {
	                    $uploaded[] = $upload_dir['baseurl'] . '/weadding-photos/' . $filename;
	                }
	            }
	        }
	    }

	    // Store uploaded URLs in WP option
	    $stored_images = get_option('my_uploaded_images', []);
	    $stored_images = array_merge($stored_images, $uploaded);
	    update_option('my_uploaded_images', $stored_images);

	    wp_send_json_success($uploaded);
	}

	public function live_images_handler() {

		$response = array(
			'status'  => 0,
			'message' => __( 'Unauthorized!', 'codesigner' ),
		);

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], $this->slug ) ) {
			wp_send_json( $response );
		}

	    $upload_dir = wp_upload_dir();
	    $custom_dir = $upload_dir['basedir'] . '/weadding-photos';
	    $custom_url = $upload_dir['baseurl'] . '/weadding-photos';

	    $images = [];

	    if (file_exists($custom_dir)) {
	        $files = scandir($custom_dir);
	        foreach ($files as $file) {
	            if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
	                $images[] = $custom_url . '/' . $file;
	            }
	        }
	    }

	    wp_send_json_success($images);
	}

}