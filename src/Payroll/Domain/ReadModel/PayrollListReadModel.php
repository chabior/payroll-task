<?php

declare(strict_types=1);

namespace App\Payroll\Domain\ReadModel;

use App\Employee\Domain\Event\EmployeeHired;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\Event\BonusSalaryCalculated;
use Doctrine\DBAL\Connection;

class PayrollListReadModel
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function handleBonusSalaryCalculated(BonusSalaryCalculated $bonusSalaryCalculated): void
    {
        $affected = $this->connection->update(
            'payroll_list',
            [
                'base_salary' => $bonusSalaryCalculated->getBaseSalary()->getValue(),
                'bonus_salary' => $bonusSalaryCalculated->getBonusSalary()->getValue(),
                'bonus_name' => $bonusSalaryCalculated->getBonusName()->name,
                'total_salary' => $bonusSalaryCalculated->getTotalSalary()->getValue(),
                'updated_at' => $bonusSalaryCalculated->getAt()->format('Y-m-d H:i:s'),
            ],
            [
                'employee_id' => $bonusSalaryCalculated->getEmployeeId()->UUID->__toString(),
            ]
        );

        if (!$affected) {
            throw new \RuntimeException(
                sprintf(
                    'Employee not found %s',
                    $bonusSalaryCalculated->getEmployeeId()->UUID->__toString()
                )
            );
        }
    }

    public function handleEmployeeHired(EmployeeHired $employeeHired): void
    {
        $this->connection->insert(
            'payroll_list',
            [
                'employee_id' => $employeeHired->employeeId->UUID->__toString(),
                'first_name' => $employeeHired->firstName->name,
                'last_name' => $employeeHired->lastName->name,
                'department_name' => $employeeHired->departmentName->name,
                'hired_at' => $employeeHired->hiredAt->value->format('Y-m-d'),
            ],
        );
    }

    public function hasRecordFor(EmployeeId $employeeId): bool
    {
        $data = $this->connection->fetchOne('SELECT employee_id FROM payroll_list WHERE employee_id = :employee_id ', [
            'employee_id' => $employeeId->UUID->__toString(),
        ]);

        return $data !== false;
    }
}
