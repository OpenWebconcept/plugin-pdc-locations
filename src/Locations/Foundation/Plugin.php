<?php

declare(strict_types=1);

/**
 * The base of the plugin.
 */

namespace OWC\PDC\Locations\Foundation;

use OWC\PDC\Base\Foundation\Plugin as BasePlugin;

/**
 * Sets the name and version of the plugin.
 */
class Plugin extends BasePlugin
{
    /**
     * Name of the plugin.
     *
     * @const string NAME
     */
    public const NAME = 'pdc-locations';

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     *
     * @const string VERSION
     */
    public const VERSION = '2.1.1';
}
