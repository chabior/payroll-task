<?php

declare(strict_types=1);

namespace App\Payroll\Infrastructure;

use App\Employee\Domain\Event\EmployeeHired;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\Event\BonusSalaryCalculated;
use App\Payroll\Domain\ReadModel\PayrollFilters;
use App\Payroll\Domain\ReadModel\PayrollListItem;
use App\Payroll\Domain\ReadModel\PayrollListReadModel;
use Doctrine\DBAL\Connection;

class DbalPayrollListReadModel implements PayrollListReadModel
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
        /**
         * @var string|false $data
         */
        $data = $this->connection->fetchOne('SELECT employee_id FROM payroll_list WHERE employee_id = :employee_id ', [
            'employee_id' => $employeeId->UUID->__toString(),
        ]);

        return $data !== false;
    }

    public function all(PayrollFilters $filters): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('*')
            ->from('payroll_list')
            ->where('base_salary IS NOT NULL')
        ;

        $filters->filter($qb);

        $result = [];
        foreach ($qb->fetchAllAssociative() as $item) {
            $result[] = new PayrollListItem(
                (string) $item['first_name'],
                (string) $item['last_name'],
                (string) $item['department_name'],
                (int) $item['base_salary'],
                (int) $item['bonus_salary'],
                (int) $item['total_salary'],
                (string) $item['bonus_name'],
            );
        }

        return $result;
    }

    public function clearAll(): void
    {
        $this->connection->executeStatement('DELETE FROM payroll_list');
    }
}
