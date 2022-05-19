<?php

declare(strict_types=1);

namespace App\Common;

use App\Common\UUID;

interface DomainEvent
{
    public function getEventId(): UUID;
}
