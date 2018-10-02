<?php
/**
 * Entity for the openinghours.
 */

namespace OWC\PDC\Locations\Entities;

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
     * @var string
     */
    protected $now = 'now';

    public function __construct($data)
    {
        $this->data         = $data;
        $this->dateTimeZone = new \DateTimeZone($this->timeZone);
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setNow($now = 'now')
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
    protected function getOpeningHoursRaw(\DateTime $date)
    {
        $dayName = $this->getDayName($date);
        return $this->data[$dayName];
    }

    /**
     * Extracts the values from the data object.
     *
     * @param $key
     *
     * @return bool|string
     */
    public function get($key)
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
     * @param [type] $key
     * @param [type] $default
     * @return void
     */
    protected function dotNotation($key, $default = null)
    {
        $current = $this->data;
        $p       = strtok($key, '.');

        while ($p !== false) {
            if (!isset($current[$p])) {
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
     * @param bool      $fullNotation
     *
     * @return string
     */
    protected function getDayName(\DateTime $date)
    {
        $format = 'l';
        return strtolower(date($format, $date->getTimestamp()));
    }

    /**
     * @param string $time
     *
     * @return \DateTime
     */
    protected function getDateTime($time = 'now')
    {
        return new \DateTime($time, $this->dateTimeZone);
    }

    /**
     * Open now message.
     *
     * @return string
     */
    public function openNowMessage()
    {

        $date          = $this->getDateTime($this->now);
        $openCloseTime = $this->getOpeningHours($date);

        if ($this->isClosed($this->getDayName($date))) {
            return sprintf(__('Today closed', 'pdc-locations'));
        }

        $openClose = $this->getOpeningHoursRaw($this->getDateTime($this->now));
        if ($openCloseTime['open-time'] < $date && $openCloseTime['closed-time'] > $date) {
            return sprintf(__('Today open from %s to %s hour', 'pdc-locations'), $openClose['open-time'], $openClose['closed-time']);
        }

        return sprintf(__('Today closed', 'pdc-locations'));
    }

    /**
     * Checks if the checkbox 'closed' isset
     */
    public function isClosed($dayName = 'monday')
    {
        return ((null !== $this->get($dayName . '.closed')) && $this->get($dayName . '.closed') == '1');
    }

    /**
     * Returns array with open/close date objects used for date comparison
     * The setTime sets the hour and minutes from the getOpeningHoursRaw func
     *
     * @param \DateTime $date
     *
     * @return array
     */
    protected function getOpeningHours(\DateTime $date)
    {
        return array_map(function ($timestamp) use ($date) {
            if (empty($timestamp)) {
                return;
            }

            $delimiter = ":";

            //check for dutch timeformat notation
            if (false !== strpos($timestamp, '.')) {
                $delimiter = ".";
            }
            [$hours, $minutes] = explode($delimiter, $timestamp);
            return (new \DateTime($this->now, $this->dateTimeZone))->setTime($hours, $minutes);
        },
            $this->getOpeningHoursRaw($date));
    }

    /**
     * Checks if the store is open or closed the next day based on the current day.
     *
     * @return bool
     */
    public function openTomorrowMessage()
    {
        $tomorrowDate = $this->getDateTime($this->now . '+1 day');
        $openClose    = $this->getOpeningHoursRaw($tomorrowDate);

        if ($this->isWeekend($tomorrowDate)) {
            return sprintf(__('Monday open from %s to %s hour', 'pdc-locations'), $this->contactInfo['_ys_mon_open_time'], $this->contactInfo['_ys_mon_close_time']);
        }

        if ($this->isClosed($this->getDayName($tomorrowDate)) || !$openClose['open-time'] || !$openClose['closed-time']) {
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
        return (int) $this->getDayIndex($dateTime) > 5 ? true : false;
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
    public function render()
    {
        return [
            'open' => [
                'today'    => $this->openNowMessage(),
                'tomorrow' => $this->openTomorrowMessage(),
            ],
        ];
    }
}
