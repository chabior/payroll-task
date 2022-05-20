<?php

declare(strict_types=1);

namespace App\Employee\Domain\Event;

use App\Common\DomainEvent;
use App\Common\UUID;
use App\Employee\Domain\DepartmentId;
use App\Employee\Domain\DepartmentName;
use App\Employee\Domain\EmployeeId;
use App\Employee\Domain\FirstName;
use App\Employee\Domain\HiredAt;
use App\Employee\Domain\LastName;

class EmployeeHired implements DomainEvent
{
    public function __construct(
        private readonly UUID $eventId,
        public readonly EmployeeId $employeeId,
        public readonly DepartmentId $departmentId,
        public readonly DepartmentName $departmentName,
        public readonly FirstName $firstName,
        public readonly LastName $lastName,
        public readonly HiredAt $hiredAt,
    ) {
    }

    public function getEventId(): UUID
    {
        return $this->eventId;
    }
}
