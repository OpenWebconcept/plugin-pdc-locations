<?php

$weeks = array_map( function( $day ) {

	return [
			'id'   => 'pdc-location-openinghours-day-' . $day,
			'name' => __('Openinghours day '. $day, 'pdc-locations'),
			'desc' => _x('Openinghours day '. $day, 'Description for the Question of a Locations item', 'pdc-locations'),
			'type' => 'text',
	];
}, range( 1, 7));

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
					'desc' => _x('Maplink', 'Fax for the Question of a Locations item', 'pdc-locations'),
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
					'id'   => 'pdc-location-openinghours-message',
					'name' => __('Openinghours message', 'pdc-locations'),
					'desc' => _x('Openinghours message', 'Description for the Question of a Locations item', 'pdc-locations'),
					'type' => 'textarea',
				],
				//$weeks
			]
		]
	]
];

