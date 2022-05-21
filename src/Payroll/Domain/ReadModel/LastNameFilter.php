<?php

declare(strict_types=1);

namespace App\Payroll\Domain\ReadModel;

use App\Common\Filter\StringFilter;

class LastNameFilter extends StringFilter
{
    public function getProperty(): string
    {
        return 'last_name';
    }
}
