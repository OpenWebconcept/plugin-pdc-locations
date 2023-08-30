<?php

namespace OWC\PDC\Locations\Entities;

use OWC\PDC\Locations\Tests\Unit\TestCase;
use WP_Mock;

class TestOpeningHours extends TestCase
{
    protected $config;

    protected $plugin;

    protected function setUp(): void
    {
        WP_Mock::setUp();
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_renders_the_output_correctly()
    {
        $data = [
            'monday'    =>
            [
                'open-time'   => '12:00',
                'closed-time' => '17:00',
                'closed'      => '1',
            ],
            'tuesday'   => [
                'open-time'   => '09:00',
                'closed-time' => '17:00',
            ],
            'wednesday' => [
                'open-time'   => '12:00',
                'closed-time' => '18:00',
            ],
            'thursday'  => [
                'open-time'   => '09:00',
                'closed-time' => '18:00',
            ],
            'friday'    => [
                'open-time'   => '12:00',
                'closed-time' => '12:30',
            ],
            'saturday'  => [
                'closed' => '1',
            ],
            'sunday'    => [
                'closed' => '1',
            ],
        ];

        $openinghoursMessage = new OpeningHours($data);
        $openinghoursMessage->setNow('Thu, 27 September 2018 13:17:00');

        $expected = $openinghoursMessage->getMessages();

        $actual = [
            'open' => [
                'today'    => sprintf(
                    __('Now open from %s to %s hour', 'pdc-locations'),
                    '09:00',
                    '18:00'
                ),
                'tomorrow' => sprintf(
                    __('Tomorrow open from %s to %s hour', 'pdc-locations'),
                    '12:00',
                    '12:30'
                ),
            ],
        ];

        $this->assertSame($expected, $actual);
    }
}
