<?php
/**
 * Entity for the custom openinghours.
 */

namespace OWC\PDC\Locations\Entities;

use OWC\PDC\Locations\Entities\Day;

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

    public function addDay($name = '', Day $day)
    {
        $this->days[$name][] = $day;
    }

    /**
     * Return day object
     *
     * @param string $name
     * @return Day
     */
    public function getDay($name)
    {
        return reset($this->days[$name]);
    }
}
