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
    /** @var DateTime */
    protected $date;

    /**
     * @param DateTime $date
     */
    final public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @param DateTime $date
     *
     * @return self
     */
    public static function make(DateTime $date): self
    {
        return new static($date);
    }

    public function format(string $format = 'H:i'): string
    {
        return $this->date->format($format);
    }

    public function get(): DateTime
    {
        return $this->date;
    }
}
