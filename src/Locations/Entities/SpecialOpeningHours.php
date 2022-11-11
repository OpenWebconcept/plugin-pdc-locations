<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Entities;

class SpecialOpeningHours extends Openinghours
{
    public function make()
    {
        if (empty($this->data['pdc-special-openings']) || ! is_array($this->data['pdc-special-openings'])) {
            return [];
        }

        return $this->castMetaboxData();
    }

    /**
     * Check if the provided weekday is a special day in this week.
     */
    public function asPossibleSpecial(Day $day): Day
    {
        $dayDateInCurrentWeek = (new \DateTime($day->getName() . ' this week'))->format('Y-m-d');

        foreach ($this->data as $specialDay) {
            $specialDate = (new \DateTime($specialDay['date']))->format('Y-m-d');

            if ($specialDate === $dayDateInCurrentWeek) {
                $day->setTimeslot(Timeslot::make($specialDay));
                unset($specialDay['date']);

                $day->makeSpecial();

                return $day;
            }
        }

        return $day;
    }

    protected function castMetaboxData(): array
    {
        $this->data['pdc-special-openings'] = array_map(
            fn ($specialDay) => $this->castSingle($specialDay),
            $this->data['pdc-special-openings']
        );

        return $this->data['pdc-special-openings'];
    }

    protected function castSingle($specialOpening)
    {
        $day    = $specialOpening['pdc-special-opening'];
        $asDate = $this->asDateInCurrentYear($day['pdc-special-opening-date']);
        $closed = isset($day['pdc-special-opening-closed']) ? (bool) $day['pdc-special-opening-closed'] : null;

        return [
            'date'        => $asDate,
            'message'     => $day['pdc-special-opening-msg'] ?? '',
            'open-time'   => $day['pdc-special-opening-time-open'] ?? null,
            'closed-time' => $day['pdc-special-opening-time-close'] ?? null,
            'closed'      => $closed,
        ];
    }

    /*
    *  M-D -> M-D-Y
    */
    protected function asDateInCurrentYear(string $date): string
    {
        return $this->getDateTime($date . '-' . date('Y'))->format(\DateTime::ATOM);
    }
}
