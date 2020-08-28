<?php

/**
 * Entity for the custom openinghours.
 */

namespace OWC\PDC\Locations\Entities;

use DateTime;

/**
 * Entity for the openinghours.
 */
class Timeslot
{
    use Timezone;

    /**
     * Array of data from the timeslot.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Current date string.
     *
     * @var string
     */
    protected $now = 'now';

    final public function __construct(array $data = [])
    {
        $this->data = $this->hydrate($data);
    }

    public static function make(array $data = []): self
    {
        return new static($data);
    }

    public function isOpen(): bool
    {
        return (true !== $this->data['closed']);
    }

    protected function hydrate(array $data): array
    {
        $default =   [
            'closed'      => false,
            'message'     => '',
            'open-time'   => null,
            'closed-time' => null,
        ];

        $data = array_merge($default, $data);

        if (!is_null($data['open-time'])) {
            $data['open-time'] = DateTime::createFromFormat('H:i', $data['open-time'], $this->getDateTimeZone());
        }

        if (!is_null($data['closed-time'])) {
            $data['closed-time'] = DateTime::createFromFormat('H:i', $data['closed-time'], $this->getDateTimeZone());
        }

        return $data;
    }

    public function getOpenTime()
    {
        return $this->data['open-time'] ?? null;
    }

    public function getTimeObject(DateTime $date): Time
    {
        return Time::make($date);
    }

    public function getClosedTime()
    {
        return $this->data['closed-time'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['message'] ?? null;
    }

    public function isOpenBetween(DateTime $time): bool
    {
        if (false === $this->getOpenTime()) {
            return false;
        }

        if (false === $this->getClosedTime()) {
            return false;
        }

        if (($this->getOpenTime() < $time) && ($this->getClosedTime() > $time)) {
            return true;
        }
        return false;
    }
}
