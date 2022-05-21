<?php

declare(strict_types=1);

namespace App\Payroll\Domain\ReadModel;

use App\Common\Filter\Order;

class FirstNameOrder extends Order
{
    public function getProperty(): string
    {
        return 'first_name';
    }
}
