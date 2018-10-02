<?php

$days = [
    1 => [
        'full' => __('Monday', 'pdc-locations'),
        'slug' => __('monday', 'pdc-locations'),
        'raw'  => 'monday',
    ],
    2 => [
        'full' => __('Tuesday', 'pdc-locations'),
        'slug' => __('tuesday', 'pdc-locations'),
        'raw'  => 'tuesday',
    ],
    3 => [
        'full' => __('Wednesday', 'pdc-locations'),
        'slug' => __('wednesday', 'pdc-locations'),
        'raw'  => 'wednesday',
    ],
    4 => [
        'full' => __('Thursday', 'pdc-locations'),
        'slug' => __('thursday', 'pdc-locations'),
        'raw'  => 'thursday',
    ],
    5 => [
        'full' => __('Friday', 'pdc-locations'),
        'slug' => __('friday', 'pdc-locations'),
        'raw'  => 'friday',
    ],
    6 => [
        'full' => __('Saturday', 'pdc-locations'),
        'slug' => __('saturday', 'pdc-locations'),
        'raw'  => 'saturday',
    ],
    7 => [
        'full' => __('Sunday', 'pdc-locations'),
        'slug' => __('sunday', 'pdc-locations'),
        'raw'  => 'sunday',
    ],
];

$weeks         = [];
$weeks['name'] = __('Openinghours', 'pdc-locations');
$weeks['id']   = 'days';
$weeks['type'] = 'group';
foreach ($days as $dayID => $day) {
    $days             = [];
    $days['name']     = __($day['full'], 'pdc-locations');
    $days['id']       = $day['raw'];
    $days['type']     = 'group';
    $days['fields'][] = [
        'id'   => 'open-time',
        'name' => __('Open from', 'pdc-locations'),
        'type' => 'text',
    ];
    $days['fields'][] = [
        'id'   => 'closed-time',
        'name' => __('Closed at', 'pdc-locations'),
        'type' => 'text',
    ];
    $days['fields'][] = [
        'id'   => 'closed',
        'name' => __('Closed?', 'pdc-locations'),
        'type' => 'checkbox',
    ];
    $days['fields'][] = [
        'id'   => 'message',
        'name' => __('Message', 'pdc-locations'),
        'type' => 'text',
        'size' => 65,
    ];
    $days['fields'][] = [
        'id'   => 'divider',
        'type' => 'divider',
    ];
    $weeks['fields'][] = $days;
}

return [
    'locations' => [
        'id'         => 'pdc-location',
        'title'      => __('Locations', 'pdc-locations'),
        'post_types' => ['pdc-location'],
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields'     => [
            'general'       => [
                [
                    'id'   => 'pdc-location-description',
                    'name' => __('Description', 'pdc-locations'),
                    'type' => 'textarea',
                ],
            ],
            'location'      => [
                [
                    'id'   => 'pdc-location-street',
                    'name' => __('Street and number', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'pdc-location-zipcode',
                    'name' => __('Zipcode', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'pdc-location-city',
                    'name' => __('City', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'pdc-location-postalcode',
                    'name' => __('Postalcode', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'pdc-location-postalcity',
                    'name' => __('Postalcity', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'pdc-location-maplink',
                    'name' => __('Maplink', 'pdc-locations'),
                    'desc' => _x('Link to external map service', 'Input for a map link for a location item', 'pdc-locations'),
                    'type' => 'text',
                ],
            ],
            'communication' => [
                [
                    'id'   => 'pdc-location-telephone-description',
                    'name' => __('Telephone description', 'pdc-locations'),
                    'type' => 'textarea',
                ],
                [
                    'id'   => 'pdc-location-telephone',
                    'name' => __('Telephone', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'pdc-location-whatsapp',
                    'name' => __('Whatsapp', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'pdc-location-fax',
                    'name' => __('Fax', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id'   => 'pdc-location-email',
                    'name' => __('Email', 'pdc-locations'),
                    'type' => 'text',
                ],
            ],
            'divider'       => [
                [
                    'type' => 'divider',
                ],
            ],
            'openinghours'  => [
                [
                    'id'   => 'pdc-location-message-active',
                    'name' => __('Openinghours message active?', 'pdc-locations'),
                    'type' => 'checkbox',
                ],
                [
                    'id'   => 'pdc-location-message',
                    'name' => __('Openinghours message', 'pdc-locations'),
                    'type' => 'textarea',
                ],
                $weeks,
            ],
        ],
    ],
];
