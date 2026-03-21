<?php
/**
 * Plugin Name: Elementor Container Carousel
 * Plugin URI: https://yosh.tools/elementor-container-carousel
 * Description: Transform any Elementor Container into a responsive carousel with Swiper.js. Two widgets: Container Wrapper and Repeater Slides.
 * Version: 1.1.4
 * Author: Jerel Yoshida
 * Author URI: https://yosh.tools
 * Text Domain: elementor-container-carousel
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

define('ECC_VERSION', '1.1.4');
define('ECC_FILE', __FILE__);
define('ECC_DIR', plugin_dir_path(__FILE__));
define('ECC_URL', plugin_dir_url(__FILE__));
define('ECC_BASENAME', plugin_basename(__FILE__));

spl_autoload_register('ecc_autoloader');

function ecc_autoloader($class)
{
    $prefix = 'Elementor_Container_Carousel\\';
    $base_dir = ECC_DIR . 'src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
}

function elementor_container_carousel()
{
    return \Elementor_Container_Carousel\Core\Plugin::get_instance();
}

add_action('plugins_loaded', 'elementor_container_carousel', 5);
