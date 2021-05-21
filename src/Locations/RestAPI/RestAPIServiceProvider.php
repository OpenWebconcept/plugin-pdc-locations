<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\RestAPI;

use \WP_REST_Server;
use OWC\PDC\Base\Foundation\ServiceProvider;

class RestAPIServiceProvider extends ServiceProvider
{

    /**
     * The endpoint of the base API.
     *
     * @var string
     */
    private $namespace = 'owc/pdc/v1';

    /**
     * Registers the locations section.
     */
    public function register()
    {
        $this->plugin->loader->addAction('rest_api_init', $this, 'registerRoutes');
        $this->plugin->loader->addFilter('owc/config-expander/rest-api/whitelist', $this, 'whitelist', 10, 1);
    }

    /**
     * Register routes on the rest API.
     *
     * Main endpoint.
     *
     * @link https://{url}/wp-json/owc/pdc/v1
     *
     * Endpoint of the locations.
     * @link https://{url}/wp-json/owc/pdc/v1/locations
     *
     * Endpoint of the location detail page.
     * @link https://{url}/wp-json/owc/pdc/v1/locations/{id}
     *
     * @return void
     */
    public function registerRoutes()
    {
        register_rest_route($this->namespace, 'locations', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [new Controllers\LocationsController($this->plugin), 'getItems'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'locations/(?P<id>\d+)', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [new Controllers\LocationsController($this->plugin), 'getItem'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Whitelist endpoints within Config Expander.
     *
     * @package OWC\ConfigExpander\DisableRestAPI\DisableRestAPI
     *
     * @param array $whitelist
     *
     * @return array
     */
    public function whitelist($whitelist): array
    {
        // Remove default root endpoint
        unset($whitelist['wp/v2']);

        $whitelist[$this->namespace] = [
            'endpoint_stub' => '/' . $this->namespace,
            'methods'       => ['GET'],
        ];

        return $whitelist;
    }
}
