<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use App\Common\UUID;
use App\Payroll\Domain\Policy\BonusSalaryPolicy;

class DepartmentBonusSalaryPolicyConfig
{
    public function __construct(
        private readonly UUID $id,
        private readonly DepartmentId $departmentId,
        public readonly BonusSalaryPolicy $bonusSalaryPolicy
    ) {
    }
}
