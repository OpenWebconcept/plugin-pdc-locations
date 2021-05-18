<?php
/**
 * PHPUnit bootstrap file
 */

/**
 * Load dependencies with Composer autoloader.
 */
require __DIR__ . '/../../vendor/autoload.php';

define('WP_PLUGIN_DIR', __DIR__);
define('PDC_LOC_FILE', __FILE__);
define('PDC_LOC_SLUG', 'pdc-locations');
define('PDC_LOC_DIR', basename(__DIR__));
define('PDC_LOC_ROOT_PATH', __DIR__.'../..');
define('PDC_LOC_VERSION', '2.1.0');


/**
 * Bootstrap WordPress Mock.
 */
\WP_Mock::setUsePatchwork(true);
\WP_Mock::bootstrap();

$GLOBALS['pdc-locations'] = [
    'active_plugins' => ['pdc-locations/pdc-locations.php'],
];

class WP_CLI
{
    public static function add_command()
    {
    }
}

if (! function_exists('get_echo')) {

    /**
     * Capture the echo of a callable function.
     *
     * @param       $callable
     * @param array $args
     *
     * @return string
     */
    function get_echo($callable, $args = [])
    {
        ob_start();
        call_user_func_array($callable, $args);

        return ob_get_clean();
    }
}
