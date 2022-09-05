<?php

declare(strict_types=1);

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

    /**
     * @return DateTimeZone
     */
    public function getDateTimeZone(): DateTimeZone
    {
        return $this->dateTimeZone = new DateTimeZone($this->timeZone);
    }
}
