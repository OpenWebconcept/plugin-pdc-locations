<?php

namespace OWC\PDC\Locations\RestAPI;

use Mockery as m;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Locations\PostTypes\PdcItem;
use OWC\PDC\Locations\RestAPI\RestAPIServiceProvider;
use OWC\PDC\Locations\Tests\Unit\TestCase;
use WP_Mock;

class RestAPIServiceProviderTest extends TestCase
{

	public function setUp()
	{
		WP_Mock::setUp();
	}

	public function tearDown()
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

		$service = new RestAPIServiceProvider($plugin);

		$this->assertTrue( true );
	}
}
