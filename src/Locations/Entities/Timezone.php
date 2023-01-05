<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

use DateTimeZone;

trait Timezone
{
    protected DateTimeZone $dateTimeZone;
    protected string $timeZone = 'Europe/Amsterdam';

    public function getDateTimeZone(): DateTimeZone
    {
        return $this->dateTimeZone = new DateTimeZone($this->timeZone);
    }
}
