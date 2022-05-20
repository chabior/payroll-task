<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Event;

use App\Common\DomainEvent;
use App\Common\UUID;
use App\Payroll\Domain\BonusName;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\Salary;
use DateTimeImmutable;
use DateTimeInterface;

class BonusSalaryCalculated implements DomainEvent
{
    public function __construct(
        private readonly UUID $eventId,
        private readonly EmployeeId $employeeId,
        private readonly Salary $baseSalary,
        private readonly Salary $bonusSalary,
        private readonly Salary $totalSalary,
        private readonly BonusName $bonusName,
        private readonly DateTimeInterface $at,
    ) {
    }

    public static function create(
        EmployeeId $employeeId,
        Salary $baseSalary,
        Salary $bonusSalary,
        Salary $totalSalary,
        BonusName $bonusName,
        DateTimeInterface $at = null
    ): self {
        if ($at === null) {
            $at = new DateTimeImmutable();
        }

        return new self(UUID::random(), $employeeId, $baseSalary, $bonusSalary, $totalSalary, $bonusName, $at);
    }

    public function getEventId(): UUID
    {
        return $this->eventId;
    }

    public function getBonusSalary(): Salary
    {
        return $this->bonusSalary;
    }

    public function getTotalSalary(): Salary
    {
        return $this->totalSalary;
    }

    public function getBaseSalary(): Salary
    {
        return $this->baseSalary;
    }

    public function getAt(): DateTimeInterface
    {
        return $this->at;
    }

    public function getEmployeeId(): EmployeeId
    {
        return $this->employeeId;
    }

    public function getBonusName(): BonusName
    {
        return $this->bonusName;
    }
}
