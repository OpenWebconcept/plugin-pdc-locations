<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

use DateInterval;
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

    /** @var string */
    protected $dateFormat = 'Y-m-d';

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function isTodayAHoliday(): bool
    {
        return $this->getDate()->format($this->dateFormat) === $this->today()->format($this->dateFormat);
    }

    public function today(): DateTime
    {
        return (new DateTime(date($this->dateFormat .' H:I:s'), new DateTimeZone($this->timeZone)));
    }

    public function isTomorrowAHoliday(): bool
    {
        $tomorrow = $this->today()->add(new DateInterval('P1D'));

        return $this->getDate()->format($this->dateFormat) === $tomorrow->format($this->dateFormat);
    }

    public function isHolidayInUpcomingWeek(): bool
    {
        $next7Days = $this->today()->add(new DateInterval('P7D'));
        return ($this->getDate()->format($this->dateFormat) >= $this->today()->format($this->dateFormat) and $this->getDate()->format($this->dateFormat) < $next7Days->format($this->dateFormat));
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
