<?php

/**
 * Provider which boots the admin serviceproviders.
 */

namespace OWC\PDC\Locations\Admin;

use OWC\PDC\Locations\Foundation\Plugin;
use OWC\PDC\Locations\Plugin\ServiceProvider;

/**
 * Provider which boots the admin serviceproviders.
 */
class Admin
{

    /**
     * Instance of the plugin.
     *
     * @var Plugin $plugin
     */
    protected $plugin;

    /**
     * Instance of the actions and filters loader.
     *
     * @var \OWC\PDC\Base\Foundation\Loader $loader
     */
    protected $loader;

    /**
     * Admin constructor.
     *
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->loader = $plugin->loader;
    }

    /**
     * Boot up the frontend
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootServiceProviders();
    }

    /**
     * Boot service providers
     *
     * @return void
     */
    private function bootServiceProviders(): void
    {
        $services = $this->plugin->config->get('core.providers.admin');

        foreach ($services as $service) {
            $service = new $service($this->plugin);

            if (!$service instanceof \OWC\PDC\Base\Foundation\ServiceProvider) {
                throw new \Exception('Provider must extend ServiceProvider.');
            }

            /**
             * @var \OWC\PDC\Base\Foundation\ServiceProvider $service
             */
            $service->register();
        }
    }
}
