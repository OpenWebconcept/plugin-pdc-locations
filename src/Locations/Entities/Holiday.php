<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

use DateTime;
use DateTimeZone;

class Holiday
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * TimeZone
     *
     * @var string
     */
    protected $timeZone = 'Europe/Amsterdam';

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function isTodayAHoliday(): bool
    {
        $now = date('Y-m-d');
        return $this->getDate()->format('Y-m-d') === $now;
    }

    public function isTomorrowAHoliday(): bool
    {
        $tomorrow = (new DateTime('Tomorrow', new DateTimeZone($this->timeZone)))
            ->format('Y-m-d');
        return $this->getDate()->format('Y-m-d') === $tomorrow;
    }

    public function isHolidayThisWeek(): bool
    {
        $monday = (new DateTime('Monday this week', new DateTimeZone($this->timeZone)))
            ->format('Y-m-d');
        $sunday = ( new DateTime('Sunday this week', new DateTimeZone($this->timeZone)))
            ->format('Y-m-d');

        return ($this->getDate()->format('Y-m-d') >= $monday and $this->getDate()->format('Y-m-d') <= $sunday);
    }

    public function getNameOfDay(): string
    {
        return strtolower($this->getDate()->format('l'));
    }

    public function getMessage(): string
    {
        return $this->data['message'];
    }

    public function getDate($format = 'Y-m-d'): DateTime
    {
        return $this->date = DateTime::createFromFormat($format, $this->data['date'], new DateTimeZone($this->timeZone));
    }

    /**
     * Returns the outline for metabox openinghours.
     *
     * @return array
     */
    public static function renderMetabox(): array
    {
        $metabox         = [
            'name'        => __('Holidays', PDC_LOC_SLUG),
            'id'          => 'day',
            'type'        => 'group',
            'clone'       => true,
            'collapsible' => true,
            'sort_clone'  => true,
            'group_title' => __('Holiday', PDC_LOC_SLUG),
            'add_button'  => __('Add new holiday', PDC_LOC_SLUG),
            'fields'      => [
                [
                    'name' => __('Holiday date', PDC_LOC_SLUG),
                    'id'   => 'date',
                    'type' => 'date',
                ],
                [
                    'name' => __('Holiday message', PDC_LOC_SLUG),
                    'id'   => 'message',
                    'type' => 'text',
                ],
            ]
        ];

        return $metabox;
    }
}
