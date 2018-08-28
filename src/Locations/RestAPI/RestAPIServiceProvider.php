<?php
/**
 * Provider which registers the Locations section to the API.
 */

namespace OWC\PDC\Locations\RestAPI;

use OWC\PDC\Base\Models\Item;
use OWC\PDC\Base\Foundation\ServiceProvider;

/**
 * Provider which registers the Locations section to the API.
 */
class RestAPIServiceProvider extends ServiceProvider
{

    /**
     * Registers the locations section.
     */
    public function register()
    {
        $this->registerModelFields();
    }

     /**
     * Register fields for all configured posttypes.
     *
     * @return void
     */
    private function registerModelFields()
    {

        // Add global fields for all Models.
        foreach ($this->plugin->config->get('api.models') as $posttype => $data) {
            foreach ($data['fields'] as $key => $creator) {
                $class = '\OWC\PDC\Base\Models\\' . ucfirst($posttype);
                if (class_exists($class)) {
                    $class::addGlobalField($key, new $creator($this->plugin));
                }
            }
        }
    }
}
