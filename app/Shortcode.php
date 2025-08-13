<?php
/**
 * All Shortcode related functions
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

    public function image_upload_shortcode() {
        ob_start();
        ?>
        <div id="my-upload-container">
            <button id="upload-btn" class="button button-primary">Upload Image(s)</button>
            <button id="show-images-btn" class="button">Show All Images</button>

            <!-- Progress Bar -->
            <div id="upload-progress" style="display:none;margin-top:10px;">
                <progress value="0" max="100" style="width:100%;"></progress>
                <span id="upload-percent">0%</span>
            </div>

            <!-- Gallery -->
            <div id="image-gallery" class="image-grid" style="display:none;"></div>
        </div>
        <?php
        return ob_get_clean();

    }
    
}