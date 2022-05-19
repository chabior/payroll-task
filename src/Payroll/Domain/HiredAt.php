<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use DateTimeInterface;

class HiredAt
{
    public function __construct(private readonly DateTimeInterface $date)
    {
    }

    public function yearsDiffFrom(\DateTimeInterface $at): int
    {
        $diff = $this->date->diff($at);

        return (int) $diff->format('%y');
    }

    public function before(DateTimeInterface $at): bool
    {
        return $at < $this->date;
    }
}
