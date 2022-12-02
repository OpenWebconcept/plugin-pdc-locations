<?php

declare(strict_types=1);

/**
 * Plugin Name:       PDC Locations
 * Plugin URI:        https://www.openwebconcept.nl/
 * Description:       Plugin to attach locations to a PDC item.
 * Version:           2.0.12
 * Author:            Yard Digital Agency
 * Author URI:        https://www.yard.nl/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       pdc-locations
 * Domain Path:       /languages
 */

use OWC\PDC\Locations\Autoloader;
use OWC\PDC\Locations\Foundation\Plugin;

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';
$autoloader = new Autoloader();

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
add_action('plugins_loaded', function () {
    $plugin = (new Plugin(__DIR__))->boot();
}, 10);
