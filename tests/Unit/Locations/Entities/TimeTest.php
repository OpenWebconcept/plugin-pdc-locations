<?php

namespace Tests\OWC\PDC\Locations\Entities;

use DateTime;
use OWC\PDC\Locations\Entities\Time;
use Symfony\Bridge\PhpUnit\ClockMock;
use Tests\OWC\PDC\Locations\TestCase;
use WP_Mock;

class TimeTest extends TestCase
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
    public function that_it_is_an_instance_of_time()
    {
        $time = new Time(new DateTime());
        $this->assertInstanceOf(Time::class, $time);

        $time = Time::make(new DateTime());
        $this->assertInstanceOf(Time::class, $time);
    }

    /** @test */
    public function that_a_datetime_object_is_returned()
    {
        $time = new Time(new DateTime());
        $this->assertInstanceOf(DateTime::class, $time->get());
    }

    /** @test */
    public function that_the_correct_format_is_returned()
    {
        ClockMock::register(Holiday::class);
        ClockMock::withClockMock(strtotime('2021-01-01 15:00:00'));

        $time = new Time(new DateTime('2021-01-01 15:00:00'));
        $this->assertEquals('15:00', $time->format());
        $this->assertEquals('03:00 PM', $time->format('h:i A'));
        $this->assertEquals('03:00:00 PM', $time->format('h:i:s A'));
    }
}
