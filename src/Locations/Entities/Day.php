<?php
/**
 * Entity for the custom openinghours.
 */

namespace OWC\PDC\Locations\Entities;

use OWC\PDC\Locations\Entities\Timeslot;

/**
 * Entity for the openinghours.
 */
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
