<?php

namespace OWC\PDC\Locations\Admin;

use Exception;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\ServiceProvider;
use OWC\PDC\Locations\Foundation\Plugin;

class Admin
{
    /**
     * Instance of the plugin.
     */
    protected Plugin $plugin;

    /**
     * Instance of the actions and filters loader.
     */
    protected Loader $loader;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->loader = $plugin->loader;
    }

    /**
     * Boot up the frontend
     */
    public function boot(): void
    {
        $this->bootServiceProviders();
    }

    /**
     * Boot service providers
     */
    private function bootServiceProviders(): void
    {
        $services = $this->plugin->config->get('core.providers.admin');

        foreach ($services as $service) {
            $service = new $service($this->plugin);

            if (!$service instanceof ServiceProvider) {
                throw new Exception('Provider must extend ServiceProvider.');
            }

            $service->register();
        }
    }
}
