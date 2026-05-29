<?php

namespace App\Service;

class MoonService
{
    private const SYNODIC_MONTH   = 29.530588853;
    private const REF_NEW_MOON_JD = 2451550.1;

    public function getWeek(?\DateTimeInterface $from = null): array
    {
        $from = $from ?? new \DateTimeImmutable('today', new \DateTimeZone('UTC'));
        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[] = $this->getPhase($from->modify("+{$i} day"));
        }
        return $week;
    }

    public function getPhase(\DateTimeInterface $date): array
    {
        $jd  = $this->toJulianDay($date);
        $age = fmod($jd - self::REF_NEW_MOON_JD, self::SYNODIC_MONTH);
        if ($age < 0) {
            $age += self::SYNODIC_MONTH;
        }

        $illumination = (1 - cos(2 * M_PI * $age / self::SYNODIC_MONTH)) / 2;
        $waxing       = $age < (self::SYNODIC_MONTH / 2);
        $name         = $this->phaseName($age);
        $shadowSide   = $waxing ? 'left' : 'right';

        if ($illumination < 0.5) {
            $type        = 'crescent';
            $ellipseScale = 1 - 2 * $illumination;
            $ellipseFill  = 'shadow';
        } else {
            $type        = 'gibbous';
            $ellipseScale = 2 * $illumination - 1;
            $ellipseFill  = 'bright';
        }

        return [
            'date'         => $date->format('Y-m-d'),
            'short'        => strtoupper($date->format('D')),
            'long'         => $date->format('l'),
            'age_days'     => round($age, 1),
            'illumination' => (int) round($illumination * 100),
            'name'         => $name,
            'short_name'   => $this->shortName($name),
            'waxing'       => $waxing,
            'shadow_side'  => $shadowSide,
            'phase_type'   => $type,
            'ellipse_scale' => round($ellipseScale, 3),
            'ellipse_fill'  => $ellipseFill,
            'visibility'   => $this->visibilityHint($illumination, $waxing),
        ];
    }

    private function toJulianDay(\DateTimeInterface $date): float
    {
        $utc = new \DateTimeImmutable($date->format('Y-m-d H:i:s'), new \DateTimeZone('UTC'));
        return $utc->getTimestamp() / 86400.0 + 2440587.5;
    }

    private function phaseName(float $age): string
    {
        $c = self::SYNODIC_MONTH;
        foreach ([
            [0.00,  1.85,  'New Moon'],
            [1.85,  5.54,  'Waxing Crescent'],
            [5.54,  9.23,  'First Quarter'],
            [9.23,  12.92, 'Waxing Gibbous'],
            [12.92, 16.61, 'Full Moon'],
            [16.61, 20.30, 'Waning Gibbous'],
            [20.30, 23.99, 'Last Quarter'],
            [23.99, 27.68, 'Waning Crescent'],
            [27.68, $c,    'New Moon'],
        ] as [$start, $end, $label]) {
            if ($age >= $start && $age < $end) {
                return $label;
            }
        }
        return 'New Moon';
    }

    private function shortName(string $full): string
    {
        return match ($full) {
            'New Moon'        => 'New',
            'Waxing Crescent' => 'Wax. Crescent',
            'First Quarter'   => 'First Qtr',
            'Waxing Gibbous'  => 'Wax. Gibbous',
            'Full Moon'       => 'Full',
            'Waning Gibbous'  => 'Wan. Gibbous',
            'Last Quarter'    => 'Last Qtr',
            'Waning Crescent' => 'Wan. Crescent',
            default           => $full,
        };
    }

    private function visibilityHint(float $illum, bool $waxing): string
    {
        if ($illum < 0.05) return 'Barely visible — dark sky';
        if ($illum < 0.25) return $waxing ? 'Evening sky, sets early' : 'Pre-dawn sky';
        if ($illum < 0.55) return $waxing ? 'Visible until midnight'  : 'Rises before midnight';
        if ($illum < 0.85) return $waxing ? 'Bright through the night': 'Sets in the morning';
        return 'Bright all night';
    }
}
