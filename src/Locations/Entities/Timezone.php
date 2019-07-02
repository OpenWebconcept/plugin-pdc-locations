<?php
/**
 * Entity for the custom openinghours.
 */

namespace OWC\PDC\Locations\Entities;

use DateTimeZone;

/**
 * Entity for the openinghours.
 */
trait Timezone
{

    /**
     * DateTimeZone object
     *
     * @var DateTimeZone
     */
    protected $dateTimeZone;

    /**
     * TimeZone
     *
     * @var string
     */
    protected $timeZone = 'Europe/Amsterdam';

    public function getDateTimeZone()
    {
        return $this->dateTimeZone = new DateTimeZone($this->timeZone);
    }
}
