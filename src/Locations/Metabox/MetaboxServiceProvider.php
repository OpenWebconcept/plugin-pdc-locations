<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Metabox;

use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Base\Foundation\ServiceProvider;

/**
 * Registers the metabox field.
 *
 * This is achieved based on the config key "metaboxes.locations".
 */
class MetaboxServiceProvider extends ServiceProvider
{

    /**
     * Register metaboxes for locations.
     */
    public function register()
    {
        $this->plugin->loader->addAction('owc/pdc-base/plugin', $this, 'registerMetaboxes', 10, 1);
    }

    /**
     * Register metaboxes for settings page into pdc-base plugin.
     *
     * @param Plugin $basePlugin
     */
    public function registerMetaboxes(Plugin $basePlugin)
    {
        $configMetaboxes = $this->plugin->config->get('metaboxes');
        $basePlugin->config->set('metaboxes.locations', $configMetaboxes['locations']);
    }
}
