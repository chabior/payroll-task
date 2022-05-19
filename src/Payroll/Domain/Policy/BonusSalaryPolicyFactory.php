<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Policy;

use App\Payroll\Domain\DepartmentId;
use App\Payroll\Domain\Employee;
use App\Payroll\Domain\PercentageSalaryRatio;
use App\Payroll\Domain\YearlyBonus;

class BonusSalaryPolicyFactory
{
    public function forEmployee(Employee $employee): BonusSalaryPolicy
    {
        return match ($employee->getDepartmentId()) {
            new DepartmentId('1') => new PercentageBonusSalaryPolicy(new PercentageSalaryRatio(0.1)),
            new DepartmentId('2') => new YearlyBonusSalaryPolicy(new YearlyBonus(10000)),
            default => new NoBonusPolicy(),
        };
    }
}
