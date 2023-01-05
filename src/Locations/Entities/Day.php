<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

class Day
{
    protected string $name = '';
    protected array $timeslots = [];
    protected bool $isSpecial = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addTimeslot(Timeslot $timeslot): void
    {
        $this->timeslots[] = $timeslot;
    }

    public function setTimeslot(Timeslot $timeslot): void
    {
        $this->timeslots = [$timeslot];
    }

    public function getName(): string
    {
        return $this->name;
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

    /**
     * Returns a REST representation of the timeslots
     */
    public function toRest(): array
    {
        return array_map(fn ($timeslot) => $timeslot->toRest(), $this->timeslots);
    }

    public function makeSpecial(): void
    {
        $this->isSpecial = true;
    }

    public function isSpecial(): bool
    {
        return $this->isSpecial;
    }
}
