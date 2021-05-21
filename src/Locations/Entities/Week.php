<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

class Week
{
    /**
     * Array of data from the week.
     *
     * @var array
     */
    protected $days = [];

    public function addDay(string $name = '', Day $day): self
    {
        $this->days[$name][] = $day;

        return $this;
    }

    /**
     * Return day object
     */
    public function getDay(string $name): Day
    {
        return reset($this->days[$name]);
    }

    /**
     * Get array of data from the week.
     */
    public function getDays(): array
    {
        return $this->days;
    }
}
