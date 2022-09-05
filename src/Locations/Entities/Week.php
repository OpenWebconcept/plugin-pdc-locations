<?php

declare(strict_types=1);

/**
 * Entity for the custom openinghours.
 */

namespace OWC\PDC\Locations\Entities;

/**
 * Entity for the openinghours.
 */
class Week
{
    /**
     * Array of data from the week.
     *
     * @var array
     */
    protected $days = [];

    /**
     * @param string $name
     * @param Day $day
     *
     * @return void
     */
    public function addDay(string $name = '', Day $day): void
    {
        $this->days[$name][] = $day;
    }

    /**
     * Return day object
     *
     * @param string $name
     *
     * @return Day
     */
    public function getDay(string $name): Day
    {
        return reset($this->days[$name]);
    }
}
