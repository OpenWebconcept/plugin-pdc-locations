<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

class CustomOpeninghours extends Openinghours
{
    protected Week $week;
    protected int $locationID;
    protected Week $regularWeek;

    public function __construct(Week $week, int $locationID = 0, Week $regularWeek)
    {
        $this->week = $week;
        $this->locationID = $locationID;
        $this->regularWeek = $regularWeek;
        parent::__construct([]);
    }

    /**
     * Returns the outline for metabox openinghours.
     */
    public static function renderMetabox(): array
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

    public function getMessages(bool $isCustomOpeninghours = true): array
    {
        return [
            'open' => [
                'today' => $this->openNowMessage($isCustomOpeninghours),
                'tomorrow' => $this->openTomorrowMessage($isCustomOpeninghours),
            ],
        ];
    }

    /**
     * Open now boolean value
     */
    public function isOpenNow(): bool
    {
        $openObject = $this->getOpeningHoursOpenNow($this->getDateTime($this->now));
        if (false === $openObject || ! $openObject->isOpen()) {
            return false;
        }

        return true;
    }

    /**
     * Open now message.
     */
    public function openNowMessage(bool $isCustomOpeninghours = true): string
    {
        // First check if today is a special day.
        if ($this->locationID && $specialDayMessage = $this->handleSpecialDayMessage('today')) {
            if (! empty($specialDayMessage)) {
                return $specialDayMessage;
            }
        }

        $now = $this->getDateTime($this->now);
        $openNowObject = $this->getOpeningHoursOpenNow($now);
        $timeslotMessage = $this->getOpeningDayFirstOccuringTimeslotMessage($now);

        // Check if timeslot has a message.
        if (! empty($timeslotMessage) && $isCustomOpeninghours) {
            return $timeslotMessage;
        }
        
        // Check if location is closed
        if (false === $openNowObject || ! $openNowObject->isOpen()) {
            if ($isCustomOpeninghours) {
                return $this->getNextOccuringTimeslotMessage($now, $isCustomOpeninghours) ?: sprintf(__('Now closed', 'pdc-locations'));
            }

            $openNowObject = $this->getUpcomingOpenTimeslotToday($now); // Overwrite object with first upcoming timeslot of today that is open.

            if (empty($openNowObject)) {
                return sprintf(__('Now closed', 'pdc-locations'));
            }

            return sprintf(__('Soon open from %s to %s hour', 'pdc-locations'), $openNowObject->getTimeObject($openNowObject->getOpenTime())->format(), $openNowObject->getTimeObject($openNowObject->getClosedTime())->format());
        }

        // Location is open return openinghours.
        return sprintf(__('Now open from %s to %s hour', 'pdc-locations'), $openNowObject->getTimeObject($openNowObject->getOpenTime())->format(), $openNowObject->getTimeObject($openNowObject->getClosedTime())->format());
    }

    public function openTomorrowMessage(bool $isCustomOpeninghours = true): string
    {
        $tomorrow = $this->getDateTime($this->now . '+1 day');
        $openNowObject = $this->getOpeningHoursOpenNow($tomorrow);
        $timeslotMessage = $this->getOpeningDayFirstOccuringTimeslotMessage($tomorrow);

        // First check if next monday is a special day.
        if ($this->isWeekend($tomorrow) && $this->locationID) {
            $specialDayMessage = $this->handleSpecialDayMessage('next monday');
    
            if (! empty($specialDayMessage)) {
                return $specialDayMessage;
            }
        }
            
        // Check if timeslot has a message.
        if (! empty($timeslotMessage) && $isCustomOpeninghours) {
            return $timeslotMessage;
        }

        // Regular message of upcoming timeslot.
        if ($this->isWeekend($tomorrow)) {
            $monday = $this->regularWeek->getDay('monday');
            $monday = $monday ? $monday->toRest()[0] : [];

            
            if (empty($monday)) {
                return __('Monday also closed', 'pdc-locations');
            }
            
            if (! boolval($monday['closed'])) {
                return sprintf(__('Monday open from %s to %s hour', 'pdc-locations'), $monday['open-time'], $monday['closed-time']);
            }

            return __('Monday also closed', 'pdc-locations');
        }

        // Check if location is closed.
        if (! $openNowObject || ! $openNowObject->isOpen()) {
            if ($isCustomOpeninghours) {
                return $this->getNextOccuringTimeslotMessage($tomorrow, $isCustomOpeninghours) ?: __('Tomorrow closed', 'pdc-locations');
            }

            $openNowObject = $this->getFirstOpenTimeslotTomorrow($tomorrow); // Overwrite object with first timeslot of tomorrow that is open.

            if (! $openNowObject->getOpenTime() || ! $openNowObject->getClosedTime()) {
                return __('Tomorrow closed', 'pdc-locations');
            }
        }

        // Location is open now, return openinghours.
        return sprintf(__('Tomorrow open from %s to %s hour', 'pdc-locations'), $openNowObject->getTimeObject($openNowObject->getOpenTime())->format(), $openNowObject->getTimeObject($openNowObject->getClosedTime())->format());
    }

    protected function handleSpecialDayMessage(string $when): string
    {
        $specialDays = get_post_meta($this->locationID, '_owc_pdc-special-openings', true);

        if (empty($specialDays)) {
            return '';
        }

        foreach ($specialDays as $specialDay) {
            if (date('d-m', strtotime($when)) !== $specialDay['pdc-special-opening']['pdc-special-opening-date']) {
                continue;
            }

            $message = $specialDay['pdc-special-opening']['pdc-special-opening-msg'] ?? '';
        }

        return ! empty($message) ? $message : '';
    }

    /**
     * Returns array with open/close date objects used for date comparison
     * The setTime sets the hour and minutes from the getOpeningHoursRaw func.
     *
     * @return object
     */
    protected function getOpeningHoursOpenNow(\DateTime $date)
    {
        $day = $this->week->getDay($this->getDayName($date));
        $timeslot = array_filter($day->getTimeslots(), function ($timeslot) {
            if (! $timeslot->isOpen()) {
                return false;
            }

            return ($timeslot->isOpenBetween($this->getDateTime($this->now)));
        });

        return end($timeslot) ?? $timeslot;
    }

    /**
     * Get first upcoming open timeslot of today.
     */
    protected function getUpcomingOpenTimeslotToday(\DateTime $time): ?Timeslot
    {
        $timeslots = $this->getOpeningHoursAll($time);

        if (empty($timeslots)) {
            return null;
        }

        $timeslots = $this->getUpcomingTimeSlots($timeslots);

        if (empty($timeslots)) {
            return null;
        }
        
        $timeslot = reset($timeslots);

        return $timeslot->isOpen() ? $timeslot : null;
    }

    protected function getFirstOpenTimeslotTomorrow(\DateTime $time): ?Timeslot
    {
        $timeslots = $this->getOpeningHoursAll($time);

        if (empty($timeslots)) {
            return null;
        }

        $timeslot = reset($timeslots);

        return $timeslot->isOpen() ? $timeslot : null;
    }

    /**
     * Get all the timeslots of a given day.
     * Only returns the timeslots which are not set to closed.
     */
    protected function getOpeningHoursAll(\DateTime $time): array
    {
        $day = $this->week->getDay($this->getDayName($time));
        $timeslots = $day->getTimeslots();

        if (empty($timeslots)) {
            return [];
        }
        
        return $timeslots;
    }

    /**
     * Returns the first occuring timeslot for the day.
     */
    protected function getOpeningDayFirstOccuringTimeslotMessage(\DateTime $date): string
    {
        $day = $this->week->getDay($this->getDayName($date));
        $timeslots = $day->getTimeslots();

        if (count($timeslots) === 0) {
            return '';
        }

        $timeslots = $this->getCurrentTimeslots($timeslots);

        if (empty($timeslots)) {
            return '';
        }

        if (! $timeslots[0]->isOpen()) {
            return '';
        }

        return $timeslots[0]->getMessage();
    }

    protected function getCurrentTimeslots(array $timeslots): array
    {
        $filtered = array_filter($timeslots, function ($timeslot) {
            return $timeslot->isOpenBetween(new \DateTime());
        });

        return array_values($filtered);
    }
    
    /**
     * Should be used when a location is closed at this specific moment.
     * When it is, get the first occuring timeslot when the location is open.
     */
    protected function getNextOccuringTimeslotMessage(\DateTime $time, bool $isCustomOpeninghours): string
    {
        if (! $isCustomOpeninghours) {
            return '';
        }

        $day = $this->week->getDay($this->getDayName($time));
        $upcomingTimeslot = reset($this->getUpcomingTimeSlots($day->getTimeslots()));

        if (empty($upcomingTimeslot) && $this->isToday($time)) {
            return '';
        }

        /**
         * When it's tomorrow use all the timeslots instead of the upcoming ones.
         * The upcoming ones are timeslots with a close time in the future as of now.
         */
        if (empty($upcomingTimeslot) && ! $this->isToday($time)) {
            $upcomingTimeslot = reset($day->getTimeslots());
        }

        $openTime = $upcomingTimeslot->getOpenTime() ? $upcomingTimeslot->getOpenTime()->format('H:i') : '';
        $closedTime = $upcomingTimeslot->getClosedTime() ? $upcomingTimeslot->getClosedTime()->format('H:i') : '';

        if (! $openTime || ! $closedTime || ! $upcomingTimeslot->isOpen()) {
            return '';
        }

        if (! $this->isToday($time)) {
            return sprintf(__('Tomorrow open from %s to %s hour', 'pdc-locations'), $openTime, $closedTime);
        }


        return sprintf(__('Soon open from %s to %s hour', 'pdc-locations'), $openTime, $closedTime);
    }

    /**
     * Get timeslots with a close time in the future and not currently open.
     */
    protected function getUpcomingTimeSlots(array $timeslots)
    {
        $filtered = array_filter($timeslots, function ($timeslot) {
            return $timeslot->getClosedTime() > new \DateTime() && ! $timeslot->isOpenBetween(new \DateTime());
        });

        return array_values($filtered);
    }

    /**
     * Validate if it's today by day name.
     */
    protected function isToday(\DateTime $time): bool
    {
        return $this->getDayName($time) === strtolower((new \DateTime)->format('l'));
    }
}
