<?php

declare(strict_types=1);

namespace App\Payroll\Domain\ReadModel;

use App\Common\Filter\StringFilter;

class DepartmentNameFilter extends StringFilter
{
    public function getProperty(): string
    {
        return 'department_name';
    }
}
