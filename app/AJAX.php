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

	public function my_image_upload_handler() {
	    if (!function_exists('wp_handle_upload')) {
	        require_once(ABSPATH . 'wp-admin/includes/file.php');
	    }

	    $files = $_FILES['file'];
	    $uploaded = [];

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
	                $movefile = wp_handle_upload($file, ['test_form' => false]);
	                if ($movefile && !isset($movefile['error'])) {
	                    $uploaded[] = $movefile['url'];
	                }
	            }
	        }
	    }

	    wp_send_json_success($uploaded);
	}

	public function live_images_handler() {
	    $upload_dir = wp_upload_dir();
	    $dir = $upload_dir['basedir'];
	    $url = $upload_dir['baseurl'];

	    $images = [];
	    $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));


	    foreach ($rii as $file) {
	        if ($file->isDir()) continue;
	        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
	        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
	            $images[] = str_replace($dir, $url, $file->getPathname());
	        }
	    }

	    wp_send_json_success($images);
	}

}