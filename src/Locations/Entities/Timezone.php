<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

use DateTimeZone;

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
