<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

class Week
{
    protected array $days = [];

    public function addDay(string $name = '', Day $day): void
    {
        $this->days[$name][] = $day;
    }

    /**
     * Return day object
     */
    public function getDay(string $name): ?Day
    {
        $days = $this->days[$name] ?? '';

        if (empty($days)) {
            return null;
        }

        return reset($days);
    }
}
