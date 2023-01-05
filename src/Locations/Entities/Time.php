<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

use DateTime;

class Time
{
    protected DateTime $date;

    final public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    public static function make(DateTime $date): self
    {
        return new static($date);
    }

    public function format(string $format = 'H:i'): string
    {
        return $this->date->format($format);
    }

    public function get(): DateTime
    {
        return $this->date;
    }
}
