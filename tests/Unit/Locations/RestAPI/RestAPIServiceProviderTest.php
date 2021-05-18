<?php

namespace Tests\OWC\PDC\Locations\RestAPI;

use Mockery as m;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Locations\RestAPI\RestAPIServiceProvider;
use Tests\OWC\PDC\Locations\TestCase;
use WP_Mock;

class RestAPIServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        WP_Mock::setUp();
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function check_registration_of_RestAPI()
    {
        $config = m::mock(Config::class);
        $plugin = m::mock(Plugin::class);

        $plugin->config = $config;
        $plugin->loader = m::mock(Loader::class);

        WP_Mock::userFunction('wp_parse_args')
            ->once()
            ->andReturn([]);

        WP_Mock::userFunction('get_option')
            ->once()
            ->andReturn([]);

        $service = new RestAPIServiceProvider($plugin);

        $this->assertInstanceOf('OWC\PDC\Locations\RestAPI\RestAPIServiceProvider', $service);
    }
}
