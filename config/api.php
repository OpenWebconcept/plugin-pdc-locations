<?php

return [
    'models' => [
        'item' => [
            /**
             * Custom field creators.
             *
             * [
             *      'creator'   => CreatesFields::class,
             *      'condition' => \Closure
             * ]
             */
            'fields' => [
                'locations' => OWC\PDC\Locations\RestAPI\ItemFields\LocationsField::class,
            ],
        ],
    ],
];
