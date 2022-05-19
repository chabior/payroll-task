<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Policy;

use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\Salary;
use DateTimeInterface;

class NoBonusPolicy implements BonusSalaryPolicy
{
    public function calculate(Salary $baseSalary, HiredAt $hiredAt, DateTimeInterface $at): Salary
    {
        return new Salary(0);
    }
}
