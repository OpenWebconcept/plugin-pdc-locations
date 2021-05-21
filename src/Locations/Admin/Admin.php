<?php
/**
 * Provider which boots the admin serviceproviders.
 */

namespace OWC\PDC\Locations\Admin;

use OWC\PDC\Locations\Plugin;
use OWC\PDC\Locations\Plugin\ServiceProvider;

/**
 * Provider which boots the admin serviceproviders.
 */
class Admin
{

    /**
     * Instance of the plugin.
     *
     * @var \OWC\PDC\Locations\Plugin
     */
    protected $plugin;

    /**
     * Instance of the actions and filters loader.
     *
     * @var \OWC\PDC\Locations\Plugin\Loader
     */
    protected $loader;

    /**
     * Admin constructor.
     *
     * @param \OWC\PDC\Locations\PLugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->loader = $plugin->loader;
    }

    /**
     * Boot up the frontend
     */
    public function boot()
    {
        $this->bootServiceProviders();
    }

    /**
     * Boot service providers
     */
    private function bootServiceProviders()
    {
        $services = $this->plugin->config->get('core.providers.admin');

        foreach ($services as $service) {
            $service = new $service($this->plugin);

            if (! $service instanceof ServiceProvider) {
                throw new \Exception('Provider must extend ServiceProvider.');
            }

            /**
             * @var \OWC\PDC\Locations\Plugin\ServiceProvider $service
             */
            $service->register();
        }
    }
}
