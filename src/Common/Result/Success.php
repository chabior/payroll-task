<?php

declare(strict_types=1);

namespace App\Common\Result;

use App\Common\DomainEvent;
use App\Common\Result;

final class Success extends Result
{
    /**
     * @var DomainEvent[]
     */
    protected array $events;

    public function __construct(DomainEvent ...$events)
    {
        $this->events = $events;
    }
}
