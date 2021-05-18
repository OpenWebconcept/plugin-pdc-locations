<?php

namespace Tests\OWC\PDC\Locations\Entities;

use OWC\PDC\Locations\Entities\Day;
use OWC\PDC\Locations\Entities\Week;
use Tests\OWC\PDC\Locations\TestCase;
use WP_Mock;

class WeekTest extends TestCase
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
    public function that_a_day_is_added()
    {
        $day  = new Day('monday');
        $week = new Week();
        $week->addDay('monday', $day);
        $this->assertArrayHasKey('monday', $week->getDays());
        $this->assertInstanceOf(Day::class, $week->getDay('monday'));
    }
}
