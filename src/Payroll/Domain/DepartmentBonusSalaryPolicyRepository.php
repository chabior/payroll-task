<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use App\Payroll\Domain\Policy\Exception\NotUniqueBonusSalaryConfigException;

interface DepartmentBonusSalaryPolicyRepository
{
    public function findForDepartment(DepartmentId $departmentId): ?DepartmentBonusSalaryPolicyConfig;

    /**
     * @throws NotUniqueBonusSalaryConfigException when config already exists
     */
    public function save(DepartmentBonusSalaryPolicyConfig $departmentBonusSalaryPolicyConfig): void;
}
