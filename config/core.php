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
            'type'    => 'plugin',
            'label'   => 'OpenPDC Base',
            'version' => '3.0.0',
            'file'    => 'pdc-base/pdc-base.php',
        ],
        [
            'type'    => 'plugin',
            'label'   => 'RWMB Metabox',
            'version' => '4.14.0',
            'file'    => 'meta-box/meta-box.php',
        ],
        [
            'type'  => 'class',
            'label' => '<a href="https://github.com/johnbillion/extended-cpts" target="_blank">Extended CPT library</a>',
            'name'  => 'Extended_CPT',
        ],
    ],
];
