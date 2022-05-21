<?php

declare(strict_types=1);

namespace App\Payroll\Domain\ReadModel;

use App\Common\Filter\Filters;

class PayrollFilters extends Filters
{
    public function getAllowedFilters(): array
    {
        return [
            new FirstNameFilter(),
            new LastNameFilter(),
            new DepartmentNameFilter(),
        ];
    }

    public function getAllowedOrder(): array
    {
        return [
            new FirstNameOrder(),
            new LastNameOrder(),
            new DepartmentNameOrder(),
            new BaseSalaryOrder(),
            new BonusSalaryOrder(),
            new TotalSalaryOrder(),
            new BonusNameOrder(),
        ];
    }
}
