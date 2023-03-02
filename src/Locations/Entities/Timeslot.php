<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

use DateTime;
use OWC\PDC\Locations\Traits\TimeFormatDelimiter;

class Timeslot
{
    use TimeFormatDelimiter;
    use Timezone;

    /**
     * Array of data from the timeslot.
     */
    protected array $data = [];

    /**
     * Current date string.
     */
    protected string $now = 'now';

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
        return (true !== filter_var($this->data['closed'], FILTER_VALIDATE_BOOL));
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

        if (! empty($data['open-time'])) {
            $format = sprintf('H%si', $this->getDelimiter($data['open-time'], '.')); // Check for dutch notation.
            $data['open-time'] = DateTime::createFromFormat($format, $data['open-time'], $this->getDateTimeZone());
        }

        if (! empty($data['closed-time'])) {
            $format = sprintf('H%si', $this->getDelimiter($data['closed-time'], '.')); // Check for dutch notation.
            $data['closed-time'] = DateTime::createFromFormat($format, $data['closed-time'], $this->getDateTimeZone());
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

    public function toRest(): array
    {
        return [
            'closed'      => $this->data['closed'],
            'message'     => $this->data['message'],
            'open-time'   => $this->data['open-time'] ? Time::make($this->data['open-time'])->format() : null,
            'closed-time' => $this->data['closed-time'] ? Time::make($this->data['closed-time'])->format() : null,
        ];
    }
}
