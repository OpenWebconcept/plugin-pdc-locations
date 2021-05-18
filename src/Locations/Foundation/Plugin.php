<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Foundation;

use OWC\PDC\Base\Foundation\Plugin as BasePlugin;

class Plugin extends BasePlugin
{
    /**
     * Name of the plugin.
     *
     * @var string
     */
    public const NAME = \PDC_LOC_SLUG;

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     *
     * @var string
     */
    public const VERSION = \PDC_LOC_VERSION;
}
