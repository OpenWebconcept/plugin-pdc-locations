<?php

namespace Tests\OWC\PDC\Locations\Entities;

use OWC\PDC\Locations\Entities\Holiday;
use Symfony\Bridge\PhpUnit\ClockMock;
use Tests\OWC\PDC\Locations\TestCase;
use WP_Mock;

class HolidayTest extends TestCase
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
    public function that_today_is_a_holiday()
    {
        ClockMock::register(Holiday::class);
        ClockMock::withClockMock(strtotime('2021-05-19'));
        $data = [
            'date'    => '2021-05-19',
            'message' => 'Pinksteren'
        ];
        $holiday = new Holiday($data);
        $this->assertTrue($holiday->isTodayAHoliday());
    }

    /** @test */
    public function that_tomorrow_is_a_holiday()
    {
        ClockMock::register(Holiday::class);
        ClockMock::withClockMock(strtotime('2021-05-20'));
        $data = [
            'date'    => '2021-05-21',
            'message' => 'Pinksteren'
        ];
        $holiday = new Holiday($data);
        $this->assertTrue($holiday->isTomorrowAHoliday());
    }

    /** @test */
    public function that_name_of_day_is_returned_correctly()
    {
        ClockMock::register(Holiday::class);
        ClockMock::withClockMock(strtotime('2021-05-20'));
        $data = [
            'date'    => '2021-05-20',
            'message' => 'Pinksteren'
        ];
        $holiday = new Holiday($data);
        $this->assertEquals('thursday', $holiday->getNameOfDay());
    }

    /** @test */
    public function that_the_holiday_is_this_week()
    {
        ClockMock::register(Holiday::class);
        ClockMock::withClockMock(strtotime('2021-05-20'));
        $data = [
            'date'    => '2021-05-20',
            'message' => 'Pinksteren'
        ];
        $holiday = new Holiday($data);
        $this->assertTrue($holiday->isHolidayThisWeek());
    }

    /** @test */
    public function that_the_holiday_is_not_this_week()
    {
        ClockMock::register(Holiday::class);
        ClockMock::withClockMock(strtotime('2021-01-01'));
        $data = [
            'date'    => '2021-01-01',
            'message' => 'Pinksteren'
        ];
        $holiday = new Holiday($data);
        $this->assertFalse($holiday->isHolidayThisWeek());
    }

    /** @test */
    public function if_message_is_correctly_returned()
    {
        $data = [
            'date'    => '2021-05-19',
            'message' => 'Pinksteren'
        ];
        $holiday = new Holiday($data);
        $this->assertEquals('Pinksteren', $holiday->getMessage());
    }

    /** @test */
    public function metabox_returns_a_correct_format()
    {
        $this->assertIsArray(Holiday::renderMetabox());
    }
}
