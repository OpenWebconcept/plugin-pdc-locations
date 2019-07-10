<?php
/**
 * Entity for the custom openinghours.
 */

namespace OWC\PDC\Locations\Entities;

use DateTime;

/**
 * Entity for the openinghours.
 */
class Time
{
    protected $date;

    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    public static function make(DateTime $date)
    {
        return new static($date);
    }

    public function format($format = 'H:i')
    {
        return $this->date->format($format);
    }

    public function get()
    {
        return $this->date;
    }
}
