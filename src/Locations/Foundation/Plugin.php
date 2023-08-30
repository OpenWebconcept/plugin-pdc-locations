<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Foundation;

use OWC\PDC\Base\Foundation\Plugin as BasePlugin;

/**
 * Sets the name and version of the plugin.
 */
class Plugin extends BasePlugin
{
    /**
     * Name of the plugin.
     */
    public const NAME = 'pdc-locations';

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     */
    public const VERSION = '2.2.1';
}
