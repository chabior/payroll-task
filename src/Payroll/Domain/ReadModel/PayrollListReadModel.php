<?php

declare(strict_types=1);

namespace App\Payroll\Domain\ReadModel;

use App\Employee\Domain\Event\EmployeeHired;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\Event\BonusSalaryCalculated;

interface PayrollListReadModel
{
    public function handleBonusSalaryCalculated(BonusSalaryCalculated $bonusSalaryCalculated): void;

    public function handleEmployeeHired(EmployeeHired $employeeHired): void;

    public function hasRecordFor(EmployeeId $employeeId): bool;
}
