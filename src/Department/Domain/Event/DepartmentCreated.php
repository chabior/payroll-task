<?php

declare(strict_types=1);

namespace App\Department\Domain\Event;

use App\Common\DomainEvent;
use App\Common\UUID;
use App\Department\Domain\DepartmentId;
use App\Department\Domain\DepartmentName;

class DepartmentCreated implements DomainEvent
{
    public function __construct(
        private readonly UUID $eventId,
        public readonly DepartmentId $departmentId,
        public readonly DepartmentName $departmentName
    ) {
    }

    public function getEventId(): UUID
    {
        return $this->eventId;
    }
}
