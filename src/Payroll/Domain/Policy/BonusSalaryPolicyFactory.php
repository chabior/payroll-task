<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Policy;

use App\Payroll\Domain\DepartmentBonusSalaryPolicyRepository;
use App\Payroll\Domain\Employee;

class BonusSalaryPolicyFactory
{
    public function __construct(private readonly DepartmentBonusSalaryPolicyRepository $departmentBonusPolicyRepository)
    {
    }

    public function forEmployee(Employee $employee): BonusSalaryPolicy
    {
        $policyConfig = $this->departmentBonusPolicyRepository->findForDepartment($employee->getDepartmentId());

        return $policyConfig->bonusSalaryPolicy ?? new NoBonusPolicy();
    }
}
