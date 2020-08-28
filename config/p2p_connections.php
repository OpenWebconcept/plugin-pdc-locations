<?php

return [

    'posttypes_info' => [
        'pdc-item'        =>
            [
                'id'    => 'pdc-item',
                'title' => _x('PDC item', 'P2P titel', 'pdc-base')
            ],
        'pdc-location'       =>
            [
                'id'    => 'pdc-location',
                'title' => _x('PDC Location', 'P2P titel', 'pdc-base')
            ]
    ],
    'connections'    => [
        [
            'from'       => 'pdc-item',
            'to'         => 'pdc-location',
            'reciprocal' => true
        ]
    ]

];
