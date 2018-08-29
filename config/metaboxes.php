<?php

$days = [
	1 => __('Monday', 'pdc-locations'),
	2 => __('Tuesday', 'pdc-locations'),
	3 => __('Wednesday', 'pdc-locations'),
	4 => __('Thursday', 'pdc-locations'),
	5 => __('Friday', 'pdc-locations'),
	6 => __('Saturday', 'pdc-locations'),
	7 => __('Sunday', 'pdc-locations'),
];

$weeks = [];
$weeks['name'] = __('Openinghours', 'pdc-locations');
$weeks['id'] = 'openinghours';
$weeks['type'] = 'group';
foreach (range(1, 7) as $day) {
    $weeks['fields'][] = [
		'id' => 'pdc-location-openinghours-day-' . $day . '-open-from-time',
		'name' => __( $days[$day] .' open from', 'pdc-locations'),
		'type' => 'text',
		'columns' => 4,
    ];
    $weeks['fields'][] = [
		'id' => 'pdc-location-openinghours-day-' . $day . '-open-until-time',
		'name' => __($days[$day] . ' open until', 'pdc-locations'),
		'type' => 'text',
		'columns' => 4,
	];
	$weeks['fields'][] = [
		'id' => 'pdc-location-openinghours-day-' . $day . '-closed',
		'name' => __($days[$day] .' closed?', 'pdc-locations'),
		'type' => 'checkbox',
		'columns' => 4,
	];
	$weeks['fields'][] = [
		'id' => 'pdc-location-openinghours-day-' . $day . '-message',
		'name' => __($days[$day] . ' message', 'pdc-locations'),
		'type' => 'text',
		'size' => 100,
		'columns' => 12,
	];
	$weeks['fields'][] = [
		'id' => 'pdc-location-openinghours-day-' . $day . '-divider',
		'type' => 'divider',
		'columns' => 12,
	];



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
            'general' => [
                [
                    'id'   => 'pdc-location-description',
                    'name' => __('Description', 'pdc-locations'),
                    'desc' => _x('', 'Description for the Question of a Locations item', 'pdc-locations'),
                    'type' => 'textarea',
                ],
            ],
            'address' => [
                [
                    'id'   => 'pdc-location-address',
                    'name' => __('Address', 'pdc-locations'),
                    'desc' => _x('Address', 'Description for the Question of a Locations item', 'pdc-locations'),
                    'type' => 'textarea',
                ],
                [
                    'id'   => 'pdc-location-maplink',
                    'name' => __('Maplink', 'pdc-locations'),
                    'desc' => _x('Link to external map serivce', 'Input for a ma link for a location item', 'pdc-locations'),
                    'type' => 'text',
                ],
            ],
            'communication' => [
                [
                    'id' => 'pdc-location-telephone-description',
                    'name' => __('Telephone description', 'pdc-locations'),
                    'desc' => _x('Telephone description', 'Description for the Question of a Locations item', 'pdc-locations'),
                    'type' => 'textarea',
                ],
                [
                    'id' => 'pdc-location-telephone',
                    'name' => __('Telephone', 'pdc-locations'),
                    'desc' => _x('', 'Telephone for the Question of a Locations item', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-fax',
                    'name' => __('Fax', 'pdc-locations'),
                    'desc' => _x('Fax', 'Fax for the Question of a Locations item', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-email',
                    'name' => __('Email', 'pdc-locations'),
                    'desc' => _x('Email', 'Fax for the Question of a Locations item', 'pdc-locations'),
                    'type' => 'text',
                ],
            ],
            'divider' => [
                [
                    'id'   => 'pdc-location-openinghours-divider',
                    'type' => 'divider',
                ]
            ],
            'openinghours' => [
            [
				'id' => 'pdc-location-openinghours-message-active',
				'name' => __('Openinghours message active?', 'pdc-locations'),
				'desc' => _x('Openinghours message', 'Description for the Question of a Locations item', 'pdc-locations'),
				'type' => 'textarea',
            ],
            [
				'id' => 'pdc-location-openinghours-message',
				'name' => __('Openinghours message', 'pdc-locations'),
				'desc' => _x('Openinghours message', 'Description for the Question of a Locations item', 'pdc-locations'),
				'type' => 'textarea',
            ],
            $weeks
            ]
        ]
    ]
];

