<?php

$textTimeAttributes = [
    'data-timepicker' => json_encode([
        'timeOnlyTitle' => __('Choose a Time', 'pdc-locations'),
        'timeFormat' => 'HH:mm',
    ]),
];

function prepareOpeningHours(array $textTimeAttributes): array
{
    $days = [
        [
            'full' => __('Monday', 'pdc-locations'),
            'raw'  => 'monday',
        ],
        [
            'full' => __('Tuesday', 'pdc-locations'),
            'raw'  => 'tuesday',
        ],
        [
            'full' => __('Wednesday', 'pdc-locations'),
            'raw'  => 'wednesday',
        ],
        [
            'full' => __('Thursday', 'pdc-locations'),
            'raw'  => 'thursday',
        ],
        [
            'full' => __('Friday', 'pdc-locations'),
            'raw'  => 'friday',
        ],
        [
            'full' => __('Saturday', 'pdc-locations'),
            'raw'  => 'saturday',
        ],
        [
            'full' => __('Sunday', 'pdc-locations'),
            'raw'  => 'sunday',
        ],
    ];

    $metaboxes = [];

    foreach ($days as $day) {
        $metaboxes[] = [
            'id' => sprintf('pdc-location-openinghours-%s', $day['raw']),
            'name' => $day['full'],
            'type' => 'group',
            'options' => [
                'add_button' => __('Add new timeslot', 'pdc-location'),
                'remove_button'     => __('Remove timeslot', 'pdc-location'),
             ],
            'fields' => [
                [
                    'id' => sprintf('pdc-location-openinghours-timeslot-%s-open-time', $day['raw']),
                    'name' => __('Open from', 'pdc-locations'),
                    'type' => 'text_time',
                    'attributes' => $textTimeAttributes,
                    'time_format' => 'H:i'

                ],
                [
                    'id' => sprintf('pdc-location-openinghours-timeslot-%s-closed-time', $day['raw']),
                    'name' => __('Closed at', 'pdc-locations'),
                    'type' => 'text_time',
                    'attributes' => $textTimeAttributes,
                    'time_format' => 'H:i'
                ],
                [
                    'id' => sprintf('pdc-location-openinghours-timeslot-%s-message', $day['raw']),
                    'name' => __('Message', 'pdc-locations'),
                    'type' => 'text'
                ]
            ]
        ];
    }

    return $metaboxes;
}

function prepareExceptions(array $textTimeAttributes): array
{
    return [
        [
            'id' => 'pdc-location-openinghours-exception-day',
            'type' => 'group',
            'options' => [
                'add_button' => __('Add new custom day', 'pdc-locations'),
                'remove_button' => __('Remove custom day', 'pdc-locations'),
            ],
            'fields' => [
                [
                    'id' => 'pdc-location-openinghours-timeslot-exception-date',
                    'name' => __('Date', 'pdc-locations'),
                    'type' => 'text_date',
                    'date_format' => 'd-m-Y',
                ],
                [
                    'id' => 'pdc-location-openinghours-timeslot-exception-open-time',
                    'name' => __('Open from', 'pdc-locations'),
                    'type' => 'text_time',
                    'attributes' => $textTimeAttributes,
                    'time_format' => 'H:i'
                ],
                [
                    'id' => 'pdc-location-openinghours-timeslot-exception-closed-time',
                    'name' => __('Closed at', 'pdc-locations'),
                    'type' => 'text_time',
                    'attributes' => $textTimeAttributes,
                    'time_format' => 'H:i'
                ],
                [
                    'id' => 'pdc-location-openinghours-timeslot-exception-message',
                    'name' => __('Message', 'pdc-locations'),
                    'type' => 'text'
                ]
            ]
        ]
    ];
}

return [
    'locations' => [
        'id' => 'pdc-location',
        'title' => __('Locatie', 'pdc-locations'),
        'object_types' => ['pdc-location'],
        'context' => 'normal',
        'priority' => 'high',
        'autosave' => true,
        'fields' => [
            'general' => [
                [
                    'id' => 'pdc-location-description',
                    'name' => __('Description', 'pdc-locations'),
                    'type' => 'textarea',
                ],
            ],
            'location' => [
                [
                    'id' => 'pdc-location-street',
                    'name' => __('Street and number', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-zipcode',
                    'name' => __('Zipcode', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-city',
                    'name' => __('City', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-postalcode',
                    'name' => __('Postalcode', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-postalcity',
                    'name' => __('Postalcity', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-maplink',
                    'name' => __('Maplink', 'pdc-locations'),
                    'desc' => __('Link to external map service', 'pdc-locations'),
                    'type' => 'text',
                ],
            ],
            'communication' => [
                [
                    'id' => 'pdc-location-telephone-description',
                    'name' => __('Telephone description', 'pdc-locations'),
                    'type' => 'textarea',
                ],
                [
                    'id' => 'pdc-location-telephone',
                    'name' => __('Telephone', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-whatsapp',
                    'name' => __('Whatsapp', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-fax',
                    'name' => __('Fax', 'pdc-locations'),
                    'type' => 'text',
                ],
                [
                    'id' => 'pdc-location-email',
                    'name' => __('Email', 'pdc-locations'),
                    'type' => 'text',
                ],
            ],
        ]
    ],
    'location-openingshours' => [
        'id' => 'pdc-location-openinghours',
        'title' => __('Openingsuren', 'pdc-locations'),
        'object_types' => ['pdc-location'],
        'context' => 'normal',
        'priority' => 'high',
        'classes' => 'owc-openinghours-wrapper',
        'autosave' => true,
        'fields' => [
            'openinghours' => prepareOpeningHours($textTimeAttributes)
        ]
    ],
    'location-openingshours-exceptions' => [
        'id' => 'pdc-location-openinghours-exceptions',
        'title' => __('Custom days', 'pdc-locations'),
        'object_types' => ['pdc-location'],
        'context' => 'normal',
        'priority' => 'high',
        'classes' => 'owc-openinghours-wrapper',
        'autosave' => true,
        'fields' => [
            'exceptions' => prepareExceptions($textTimeAttributes)
        ]
    ]
];
