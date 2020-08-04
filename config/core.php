<?php

return [
    /**
             * Service Providers.
         */
    'providers' => [
        /**
         * Global providers.
         */
        OWC\PDC\Locations\RestAPI\RestAPIServiceProvider::class,
        OWC\PDC\Locations\PostType\LocationsPostTypeServiceProvider::class,
        OWC\PDC\Locations\PostsToPosts\PostsToPostsServiceProvider::class,
        /**
         * Providers specific to the admin.
         */
        'admin' => [
            OWC\PDC\Locations\Metabox\MetaboxServiceProvider::class,
        ],
    ],

    'dependencies' => [
        [
            'type' => 'plugin',
            'label' => 'OpenPDC Base',
            'version' => '3.0.0',
            'file' => 'pdc-base/pdc-base.php',
        ],
    ],
];
