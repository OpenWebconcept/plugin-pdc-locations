<?php

namespace OWC\PDC\Locations\Entities;

use OWC\PDC\Locations\Tests\Unit\TestCase;
use WP_Mock;

class TestOpeninghours extends TestCase
{

    protected $config;

    protected $plugin;

    public function setUp()
    {
        WP_Mock::setUp();
    }

    public function tearDown()
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_renders_the_output_correctly()
    {
        $data = [
            'monday' =>
            [
                'open-time' => '12:00',
                'closed-time' => '17:00',
                'closed' => '1',
            ],
            'tuesday' => [
                'open-time' => '09:00',
                'closed-time' => '17:00',
            ],
            'wednesday' => [
                'open-time' => '12:00',
                'closed-time' => '18:00',
            ],
            'thursday' => [
                'open-time' => '09:00',
                'closed-time' => '18:00',
            ],
            'friday' => [
                'open-time' => '12:00',
                'closed-time' => '12:30',
            ],
            'saturday' => [
                'closed' => '1',
            ],
            'sunday' => [
                'closed' => '1',
            ],
        ];

		$openinghoursMessage = new Openinghours($data);

		$openinghoursMessage->setNow('Thu, 27 September 2018 13:17:00');

        $expected = $openinghoursMessage->render();
        $actual = [
            'open' => [
                'today' => "Vandaag open van 09:00 tot 18:00 uur",
                'tomorrow' => "Morgen open van 12:00 tot 12:30 uur",
            ],
        ];

        $this->assertSame($expected, $actual);
    }
}
