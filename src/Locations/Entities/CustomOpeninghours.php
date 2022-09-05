<?php

declare(strict_types=1);

/**
 * Entity for the custom openinghours.
 */

namespace OWC\PDC\Locations\Entities;

/**
 * Entity for the openinghours.
 */
class CustomOpeninghours extends Openinghours
{
    /** @var Week */
    protected $week;

    public function __construct(Week $week)
    {
        $this->week = $week;
        parent::__construct([]);
    }

    /**
     * Returns the outline for metabox openinghours.
     *
     * @return array
     */
    public static function renderMetabox()
    {
        $daysDefault = [
            1 => [
                'full' => __('Monday', 'pdc-locations'),
                'slug' => __('monday', 'pdc-locations'),
                'raw'  => 'monday',
            ],
            2 => [
                'full' => __('Tuesday', 'pdc-locations'),
                'slug' => __('tuesday', 'pdc-locations'),
                'raw'  => 'tuesday',
            ],
            3 => [
                'full' => __('Wednesday', 'pdc-locations'),
                'slug' => __('wednesday', 'pdc-locations'),
                'raw'  => 'wednesday',
            ],
            4 => [
                'full' => __('Thursday', 'pdc-locations'),
                'slug' => __('thursday', 'pdc-locations'),
                'raw'  => 'thursday',
            ],
            5 => [
                'full' => __('Friday', 'pdc-locations'),
                'slug' => __('friday', 'pdc-locations'),
                'raw'  => 'friday',
            ],
            6 => [
                'full' => __('Saturday', 'pdc-locations'),
                'slug' => __('saturday', 'pdc-locations'),
                'raw'  => 'saturday',
            ],
            7 => [
                'full' => __('Sunday', 'pdc-locations'),
                'slug' => __('sunday', 'pdc-locations'),
                'raw'  => 'sunday',
            ],
        ];

        $fieldsPerDay = [
            [
                'id'   => 'open-time',
                'name' => __('Open from', 'pdc-locations'),
                'type' => 'time',
            ],
            [
                'id'   => 'closed-time',
                'name' => __('Closed at', 'pdc-locations'),
                'type' => 'time',
            ],
            [
                'id'   => 'closed',
                'name' => __('Closed?', 'pdc-locations'),
                'type' => 'checkbox',
            ],
            [
                'id'   => 'message',
                'name' => __('Message', 'pdc-locations'),
                'type' => 'text',
                'size' => 65,
            ],
        ];

        $weeks         = [];
        $weeks['name'] = __('Custom openinghours', 'pdc-locations');
        $weeks['id']   = 'custom-days';
        $weeks['type'] = 'group';
        foreach ($daysDefault as $day) {
            $dayGroup                = [];
            $dayGroup['name']        = __($day['full'], 'pdc-locations');
            $dayGroup['id']          = $day['raw'];
            $dayGroup['type']        = 'group';
            $dayGroup['clone']       = true;
            $dayGroup['collapsible'] = true;
            $dayGroup['sort_clone']  = true;
            $dayGroup['add_button']  = __('Add new time', 'pdc-locations');
            $dayGroup['group_title'] = '{open-time} - {closed-time}';
            $dayGroup['fields']      = $fieldsPerDay;
            $weeks['fields'][]       = $dayGroup;
        }

        return $weeks;
    }

    public function getMessages()
    {
        return [
            'open' => [
                'today'    => $this->openNowMessage(),
                'tomorrow' => $this->openTomorrowMessage(),
            ],
        ];
    }

    /**
     * Open now boolean value
     */
    public function isOpenNow(): bool
    {
        $openObject = $this->getOpeningHours($this->getDateTime($this->now));
        if (false === $openObject || ! $openObject->isOpen()) {
            return false;
        }

        return true;
    }

    /**
     * Open now message.
     */
    public function openNowMessage(): string
    {
        $now            = $this->getDateTime($this->now);
        $openObject     = $this->getOpeningHours($now);
        $specialMessage = $this->getOpeningDayFirstTimeslotMessage($now);

        if (! empty($specialMessage)) {
            return $specialMessage;
        }
        
        if (false === $openObject || ! $openObject->isOpen()) {
            return sprintf(__('Now closed', 'pdc-locations'));
        }

        return sprintf(__('Now open from %s to %s hour', 'pdc-locations'), $openObject->getTimeObject($openObject->getOpenTime())->format(), $openObject->getTimeObject($openObject->getClosedTime())->format());
    }

    public function openTomorrowMessage(): string
    {
        $tomorrow       = $this->getDateTime($this->now . '+1 day');
        $openObject     = $this->getOpeningHours($tomorrow);
        $specialMessage = $this->getOpeningDayFirstTimeslotMessage($tomorrow);

        if (! empty($specialMessage)) {
            return $specialMessage;
        }

        if (false === $openObject || ! $openObject->isOpen()) {
            return sprintf(__('Now closed', 'pdc-locations'));
        }

        return sprintf(__('Now open from %s to %s hour', 'pdc-locations'), $openObject->getTimeObject($openObject->getOpenTime())->format(), $openObject->getTimeObject($openObject->getClosedTime())->format());
    }

    /**
     * Returns array with open/close date objects used for date comparison
     * The setTime sets the hour and minutes from the getOpeningHoursRaw func.
     *
     * @param \DateTime $date
     *
     * @return object
     */
    protected function getOpeningHours(\DateTime $date)
    {
        $day      = $this->week->getDay($this->getDayName($date));
        $timeslot = array_filter($day->getTimeslots(), function ($timeslot) {
            if (! $timeslot->isOpen()) {
                return false;
            }

            return ($timeslot->isOpenBetween($this->getDateTime($this->now)));
        });

        return end($timeslot) ?? $timeslot;
    }

    /**
     * Returns the first timeslot for the day.
     */
    protected function getOpeningDayFirstTimeslotMessage(\DateTime $date): string
    {
        $day       = $this->week->getDay($this->getDayName($date));
        $timeslots = $day->getTimeslots();

        if (count($timeslots) === 0) {
            return '';
        }

        return $day->getTimeslots()[0]->getMessage();
    }
}
