<?php

namespace OWC\PDC\Locations\ElasticSearch;

use Mockery as m;
use OWC\PDC\Locations\Config;
use OWC\PDC\Locations\Foundation\Plugin;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Locations\Tests\Unit\TestCase;

class MetaboxServiceProviderTest extends TestCase
{

    public function setUp()
    {
        \WP_Mock::setUp();
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();
    }

    /**
     * @test 
     */
    public function check_registration_of_elasticsearch_metadata()
    {
        $config = m::mock(Config::class);
        $plugin = m::mock(Plugin::class);

        $plugin->config = $config;
        $plugin->loader = m::mock(Loader::class);

        $service = new ElasticSearchServiceProvider($plugin);

        $plugin->loader->shouldReceive('addFilter')->withArgs(
            [
            'owc/pdc-elasticsearch/elasticpress/postargs/meta',
            $service,
            'registerElasticSearchMetaData',
            10,
            2
            ]
        )->once();

        $service->register();

        $additional_prepared_meta          = [];
        $expected_additional_prepared_meta = ['location_group' => 'antwoord!!'];

        $postID = 5;

        \WP_Mock::userFunction(
            'get_post_type', [
            'args'   => $postID,
            'times'  => '1',
            'return' => 'pdc-item'
            ]
        );

        $location_group_meta = [
        0 => [
        'pdc_location_question' => 'Vraag??',
        'pdc_location_answer'   => 'antwoord!!'
        ]
        ];

        \WP_Mock::userFunction(
            'get_post_meta', [
            'args'   => [
                    $postID,
                    \WP_Mock\Functions::type('string'),
                    true
            ],
            'times'  => '1',
            'return' => $location_group_meta
            ]
        );

        $this->assertEquals($expected_additional_prepared_meta, $service->registerElasticSearchMetaData($additional_prepared_meta, $postID));

    }
}
