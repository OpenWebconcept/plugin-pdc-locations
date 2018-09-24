<?php

return [
    'models' => [
        'location' => [
            /**
             * Custom field creators.
             *
             * [
             *      'creator'   => CreatesFields::class,
             *      'condition' => \Closure
             * ]
             */
            'fields' => [
                'locations'         => OWC\PDC\Locations\RestAPI\ItemFields\LocationsField::class
            ]
        ],
    ]
];
