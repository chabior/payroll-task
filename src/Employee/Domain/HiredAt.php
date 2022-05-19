<?php

declare(strict_types=1);

namespace App\Employee\Domain;

use DateTimeInterface;

class HiredAt
{
    public function __construct(public readonly DateTimeInterface $value)
    {
    }
}
