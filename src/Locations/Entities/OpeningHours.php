<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

use DateTime;
use OWC\PDC\Locations\Traits\TimeFormatDelimiter;

class OpeningHours
{
    use TimeFormatDelimiter;

    protected array $data; // OpeningHoursDat
    protected int $postID;
    protected array $contactInfo;
    protected \DateTimeZone $dateTimeZone;
    protected string $timeZone = 'Europe/Amsterdam';
    protected $now = 'now';

    public function __construct($data, int $postID = 0)
    {
        $this->data         = $data;
        $this->postID      = $postID;
        $this->dateTimeZone = new \DateTimeZone($this->timeZone);
    }

    /**
     * Set the current date.
     */
    public function setNow($now = 'now'): void
    {
        $this->now = $now;
    }

    /**
     * Returns array with open/close times string based from the contactInfo site option
     * NOTE: these are not date objects
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
     * Return value in array by dot notation.
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
     */
    protected function getDayName(\DateTime $date): string
    {
        $format = 'l';

        return strtolower(date($format, $date->getTimestamp()));
    }

    /**
     * Get the dateTime object depending on the $time parameter.
     */
    protected function getDateTime($time = 'now'): DateTime
    {
        return new DateTime($time, $this->dateTimeZone);
    }

    /**
     * Open now boolean value
     */
    public function isOpenNow(): bool
    {
        $date = $this->getDateTime($this->now);
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
     */
    public function openNowMessage(): string
    {
        $date = $this->getDateTime($this->now);
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
     */
    public function isClosed($dayName = 'monday'): bool
    {
        return ((null !== $this->get($dayName . '.closed')) && true == $this->get($dayName . '.closed'));
    }

    /**
     * Returns array with open/close date objects used for date comparison
     * The setTime sets the hour and minutes from the getOpeningHoursRaw func.
     */
    protected function getOpeningHours(DateTime $date)
    {
        return array_map(
            function ($timestamp) {
                if (empty($timestamp) || (true === $timestamp)) {
                    return;
                }

                $delimiter = $this->getDelimiter($timestamp, '.'); // Check for dutch notation.
                list($hours, $minutes) = explode($delimiter, $timestamp);

                return (new \DateTime($this->now, $this->dateTimeZone))->setTime((int) $hours, (int) $minutes);
            },
            $this->getOpeningHoursRaw($date)
        );
    }

    /**
     * Checks if the store is open or closed the next day based on the current day.
     */
    public function openTomorrowMessage(): string
    {
        $tomorrowDate = $this->getDateTime($this->now . '+1 day');
        $openClose = $this->getOpeningHoursRaw($tomorrowDate);

        if ($this->isWeekend($tomorrowDate)) {
            if (! boolval($this->data['monday']['closed'])) {
                return sprintf(__('Monday open from %s to %s hour', 'pdc-locations'), $this->data['monday']['open-time'], $this->data['monday']['closed-time']);
            }

            return __('Monday also closed', 'pdc-locations');
        }

        if ($this->isClosed($this->getDayName($tomorrowDate)) || ! $openClose['open-time'] || ! $openClose['closed-time']) {
            return __('Tomorrow closed', 'pdc-locations');
        }

        return sprintf(__('Tomorrow open from %s to %s hour', 'pdc-locations'), $openClose['open-time'], $openClose['closed-time']);
    }

    /**
     * Check if giving date is a weekend day 6 & 7
     */
    protected function isWeekend(\DateTime $dateTime): bool
    {
        return 5 < (int) $this->getDayIndex($dateTime) ? true : false;
    }

    /**
     * Gets the daynumber of the week i.e. Monday === 1
     *
     * @return false|string
     */
    protected function getDayIndex(\DateTime $date)
    {
        return date('N', $date->getTimestamp());
    }

    /**
     * Render the output to the API.
     */
    public function getMessages(): array
    {
        return [
            'open' => [
                'today' => $this->openNowMessage(),
                'tomorrow' => $this->openTomorrowMessage(),
            ],
        ];
    }
}
