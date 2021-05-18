<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

class Day
{
    protected $name = '';

    protected $timeslots = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addTimeslot(Timeslot $timeslot)
    {
        $this->timeslots[]  = $timeslot;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getTimeslots()
    {
        return $this->timeslots;
    }
}
