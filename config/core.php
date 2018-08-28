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
        'admin'    => [
	        OWC\PDC\Locations\Metabox\MetaboxServiceProvider::class,
        ]
    ],

    'dependencies' => 	[
		[
			'label'   => 'OpenPDC Base',
			'file'    => 'pdc-base/pdc-base.php',
			'version' => '2.0.0',
			'type'    => 'plugin'
		]
	]
];
