<?php

declare(strict_types=1);

/**
 * Entity for the openinghours.
 */

namespace OWC\PDC\Locations\Entities;

use DateTime;

/**
 * Entity for the openinghours.
 */
class Openinghours
{

    /**
     * OpeninghoursData
     *
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $contactInfo;

    /**
     * DateTimeZone object
     *
     * @var \DateTimeZone
     */
    protected $dateTimeZone;

    /**
     * TimeZone
     *
     * @var string
     */
    protected $timeZone = 'Europe/Amsterdam';

    /**
     * Current date string.
     *
     * @var string
     */
    protected $now = 'now';

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data         = $data;
        $this->dateTimeZone = new \DateTimeZone($this->timeZone);
    }

    /**
     * Set the current date.
     *
     * @param string $now
     *
     * @return void
     */
    public function setNow($now = 'now'): void
    {
        $this->now = $now;
    }

    /**
     * Returns array with open/close times string based from the contactInfo site option
     * NOTE: these are not date objects
     *
     * @param \DateTime $date
     *
     * @return array
     */
    protected function getOpeningHoursRaw(\DateTime $date): array
    {
        $dayName    = $this->getDayName($date);
        $openClosed = $this->data[$dayName];

        unset($openClosed['message']);

        if (isset($openClosed['closed']) && true == $openClosed['closed']) {
            $openClosed['open-time']   = null;
            $openClosed['closed-time'] = null;
        }

        return $openClosed;
    }

    /**
     * Extracts the values from the data object.
     *
     * @param string $key
     *
     * @return bool|string
     */
    public function get(string $key)
    {
        if (false !== strpos($key, ".")) {
            return $this->dotNotation($key);
        }

        if (empty($this->data[$key])) {
            return false;
        }

        return (string) $this->data[$key];
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    protected function dotNotation(string $key, string $default = null)
    {
        $current = $this->data;
        $p       = strtok($key, '.');

        while (false !== $p) {
            if (! isset($current[$p])) {
                return $default;
            }
            $current = $current[$p];
            $p       = strtok('.');
        }

        return $current;
    }

    /**
     * Gets the dayName i.e. mon or monday when the fullNotation is true
     *
     * @param \DateTime $date
     *
     * @return string
     */
    protected function getDayName(\DateTime $date)
    {
        $format = 'l';

        return strtolower(date($format, $date->getTimestamp()));
    }

    /**
     * Get the dateTime object depending on the $time parameter.
     *
     * @param string $time
     *
     * @return DateTime
     */
    protected function getDateTime($time = 'now'): DateTime
    {
        return new DateTime($time, $this->dateTimeZone);
    }

    /**
     * Open now boolean value
     *
     * @return bool
     */
    public function isOpenNow(): bool
    {
        $date          = $this->getDateTime($this->now);
        $openCloseTime = $this->getOpeningHours($date);

        if ($this->isClosed($this->getDayName($date))) {
            return false;
        }

        if ($openCloseTime['open-time'] < $date && $openCloseTime['closed-time'] > $date) {
            return true;
        }

        return false;
    }

    /**
     * Open now message.
     *
     * @return string
     */
    public function openNowMessage(): string
    {
        $date          = $this->getDateTime($this->now);
        $openCloseTime = $this->getOpeningHours($date);

        if ($this->isClosed($this->getDayName($date))) {
            return sprintf(__('Now closed', 'pdc-locations'));
        }

        $openClose = $this->getOpeningHoursRaw($this->getDateTime($this->now));
        if ($openCloseTime['open-time'] < $date && $openCloseTime['closed-time'] > $date) {
            return sprintf(__('Now open from %s to %s hour', 'pdc-locations'), $openClose['open-time'], $openClose['closed-time']);
        }

        return sprintf(__('Now closed', 'pdc-locations'));
    }

    /**
     * Checks if the checkbox 'closed' isset.
     *
     * @param string $dayName
     *
     * return bool
     */
    public function isClosed($dayName = 'monday'): bool
    {
        return ((null !== $this->get($dayName . '.closed')) && true == $this->get($dayName . '.closed'));
    }

    /**
     * Returns array with open/close date objects used for date comparison
     * The setTime sets the hour and minutes from the getOpeningHoursRaw func.
     *
     * @param DateTime $date
     *
     * @return array
     */
    protected function getOpeningHours(DateTime $date)
    {
        return array_map(
            function ($timestamp) {
                if (empty($timestamp) || (true === $timestamp)) {
                    return;
                }

                $delimiter = ":";

                //check for dutch timeformat notation
                if (false !== strpos($timestamp, '.')) {
                    $delimiter = ".";
                }

                list($hours, $minutes) = explode($delimiter, $timestamp);

                return (new \DateTime($this->now, $this->dateTimeZone))->setTime((int) $hours, (int) $minutes);
            },
            $this->getOpeningHoursRaw($date)
        );
    }

    /**
     * Checks if the store is open or closed the next day based on the current day.
     *
     * @return string
     */
    public function openTomorrowMessage(): string
    {
        $tomorrowDate = $this->getDateTime($this->now . '+1 day');
        $openClose    = $this->getOpeningHoursRaw($tomorrowDate);

        if ($this->isWeekend($tomorrowDate)) {
            if (! boolval($this->data['monday']['closed'])) {
                return sprintf(__('Monday open from %s to %s hour', 'pdc-locations'), $this->data['monday']['open-time'], $this->data['monday']['closed-time']);
            } else {
                return __('Monday also closed', 'pdc-locations');
            }
        }

        $openClose = $this->getOpeningHoursRaw($tomorrowDate);
        if ($this->isClosed($this->getDayName($tomorrowDate)) || ! $openClose['open-time'] || ! $openClose['closed-time']) {
            return __('Tomorrow closed', 'pdc-locations');
        }

        return sprintf(__('Tomorrow open from %s to %s hour', 'pdc-locations'), $openClose['open-time'], $openClose['closed-time']);
    }

    /**
     * Check if giving date is a weekend day 6 & 7
     *
     * @param \DateTime $dateTime
     *
     * @return bool
     */
    protected function isWeekend(\DateTime $dateTime)
    {
        return 5 < (int) $this->getDayIndex($dateTime) ? true : false;
    }

    /**
     * Gets the daynumber of the week i.e. Monday === 1
     *
     * @param \DateTime $date
     *
     * @return false|string
     */
    protected function getDayIndex(\DateTime $date)
    {
        return date('N', $date->getTimestamp());
    }

    /**
     * Render the output to the API.
     *
     * @return array
     */
    public function getMessages()
    {
        return [
            'open' => [
                'today'    => $this->openNowMessage(),
                'tomorrow' => $this->openTomorrowMessage(),
            ],
        ];
    }
}
