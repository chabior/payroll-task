<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use App\Common\Result;
use App\Payroll\Domain\Event\BonusSalaryCalculated;
use App\Payroll\Domain\Policy\BonusSalaryPolicy;
use DateTimeImmutable;
use DateTimeInterface;

class Employee
{
    public function __construct(
        private readonly EmployeeId $employeeId,
        private readonly Salary $baseSalary,
        private Salary $bonusSalary,
        private readonly DepartmentId $departmentId,
        private readonly HiredAt $hiredAt
    ) {
    }

    public function calculateCurrentBonusSalary(BonusSalaryPolicy $salaryPolicy): Result
    {
        return $this->calculateBonusSalaryAt($salaryPolicy, new DateTimeImmutable());
    }

    private function calculateBonusSalaryAt(BonusSalaryPolicy $salaryPolicy, DateTimeInterface $at): Result
    {
        $this->bonusSalary = $salaryPolicy->calculate($this->baseSalary, $this->hiredAt, $at);

        return Result::success(
                BonusSalaryCalculated::create(
                    $this->employeeId,
                    $this->baseSalary,
                    $this->bonusSalary,
                    $this->baseSalary->add($this->bonusSalary),
                    $at
                )
        );
    }

    public function getDepartmentId(): DepartmentId
    {
        return $this->departmentId;
    }
}
