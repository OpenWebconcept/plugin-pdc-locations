<?php

namespace OWC\PDC\Locations\RestApi;

use Mockery as m;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Locations\RestApi\RestApiServiceProvider;
use OWC\PDC\Locations\Tests\Unit\TestCase;
use OWC\PDC\Locations\PostTypes\PdcItem;
use WP_Mock;

class RestApiServiceProviderTest extends TestCase
{

    public function setUp()
    {
        WP_Mock::setUp();
    }

    public function tearDown()
    {
        WP_Mock::tearDown();
    }

    /**
     * @test
     */
    public function check_registration_of_RestApi()
    {
        $config = m::mock(Config::class);
        $plugin = m::mock(Plugin::class);

        $plugin->config = $config;
        $plugin->loader = m::mock(Loader::class);

        $service = new RestApiServiceProvider($plugin);

        $service->register();

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function check_get_locations_for_rest_api()
    {
        $postID         = 5;
        $locations_group_meta = [
        0 => [
        'pdc_locations_question' => 'Vraag??',
        'pdc_locations_answer'   => 'antwoord!!'
        ]
        ];

        WP_Mock::userFunction(
            'get_post_meta', [
            'args'   => [
                    $postID,
                    \WP_Mock\Functions::type('string'),
                    true
            ],
            'times'  => '1',
            'return' => $locations_group_meta
            ]
        );

        $pdcItem = new PdcItem();

        $object['id'] = $postID;

        $this->assertEquals($locations_group_meta, $pdcItem->getLocationsForRestApi($object, $field_name = '', $request = ''));
    }

}
