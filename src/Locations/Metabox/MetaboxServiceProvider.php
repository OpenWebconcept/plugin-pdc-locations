<?php

/**
 * Registers the metabox field.
 */

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
     *
     * @return void
     */
    public function register(): void
    {
        $this->plugin->loader->addAction('owc/pdc-base/plugin', $this, 'registerMetaboxes', 10, 1);
    }

    /**
     * Register metaboxes for settings page into pdc-base plugin.
     *
     * @param Plugin $basePlugin
     *
     * @return void
     */
    public function registerMetaboxes(Plugin $basePlugin): void
    {
        $configMetaboxes = $this->plugin->config->get('metaboxes');
        $basePlugin->config->set('metaboxes.locations', $configMetaboxes['locations']);
    }
}
