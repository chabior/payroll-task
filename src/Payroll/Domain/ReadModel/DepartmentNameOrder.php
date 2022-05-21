<?php

declare(strict_types=1);

namespace App\Payroll\Domain\ReadModel;

use App\Common\Filter\Order;

class DepartmentNameOrder extends Order
{
    public function getProperty(): string
    {
        return 'department_name';
    }
}
