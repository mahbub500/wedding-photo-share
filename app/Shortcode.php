<?php
/**
 * All Shortcode related functions
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
 * @subpackage Shortcode
 * @author Codexpert <hi@codexpert.io>
 */
class Shortcode extends Base {

    public $plugin;

    /**
     * Constructor function
     */
    public function __construct( $plugin ) {
        $this->plugin   = $plugin;
        $this->slug     = $this->plugin['TextDomain'];
        $this->name     = $this->plugin['Name'];
        $this->version  = $this->plugin['Version'];
    }

    public function my_shortcode() {
        return __( 'My Shortcode', 'weadding-photo-share' );
    }

    public function qr_code_shortcode( $atts ) {
        $image_uplode_page_url = get_permalink( Helper::get_option( 'weadding-photo-share_basic', 'image_page' ));
        
        $atts = shortcode_atts([
            'text' => $image_uplode_page_url,
            'size' => 300, // px
        ], $atts);

        $size = (int) $atts['size'];
        $url  = esc_url('https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . rawurlencode($atts['text']));

        return '<div style="text-align:center;"><img src="' . $url . '" alt="QR code" width="' . $size . '" height="' . $size . '"></div>';
    }

    public function image_upload_shortcode() {
        ob_start();
        ?>
        <div id="my-upload-container">
            <button id="upload-btn" class="button button-primary">Upload Image(s)</button>
            <button id="show-images-btn" class="button">Show All Images</button>
            <button id="download-selected-btn" class="button button-secondary" style="display:none;">Download Selected</button>

            <!-- Progress Bar -->
            <div id="upload-progress" style="display:none;margin-top:10px;">
                <progress value="0" max="100" style="width:100%;"></progress>
                <span id="upload-percent">0%</span>
            </div>

            <!-- Gallery with separate pagination -->
            <div id="image-gallery">
                <div id="image-container" class="image-grid"></div>
                <div id="pagination-container"></div>
            </div>

        </div>
        <?php
        return ob_get_clean();

    }
    
}