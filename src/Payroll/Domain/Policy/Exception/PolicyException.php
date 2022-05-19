<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Policy\Exception;

use InvalidArgumentException;

class PolicyException extends InvalidArgumentException
{
    public static function cantCalculateBonusSalaryBeforeEmployeeWasHired(): self
    {
        return new self(
            'Can\'t calculate bonus salary before employee was hired',
            1,
        );
    }
}
