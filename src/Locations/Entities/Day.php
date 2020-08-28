<?php

/**
 * Entity for the custom openinghours.
 */

namespace OWC\PDC\Locations\Entities;

/**
 * Entity for the openinghours.
 */
class Day
{
    /** @var string */
    protected $name = '';

    /** @var array */
    protected $timeslots = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addTimeslot(Timeslot $timeslot): void
    {
        $this->timeslots[]  = $timeslot;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Timeslot[]
     */
    public function getTimeslots(): array
    {
        return $this->timeslots;
    }
}
