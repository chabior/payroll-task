<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use App\Payroll\Domain\Policy\Exception\NotUniqueEmployeeException;

interface EmployeeRepository
{
    /**
     * @return Employee[]
     */
    public function all(): array;

    /**
     * @throws NotUniqueEmployeeException when employee already exists
     */
    public function save(Employee $employee): void;
}
