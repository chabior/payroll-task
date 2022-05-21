<?php

declare(strict_types=1);

namespace App\Tests\Facade\Payroll;

use App\Common\UUID;
use App\Employee\Domain\DepartmentId;
use App\Employee\Domain\DepartmentName;
use App\Employee\Domain\EmployeeId;
use App\Employee\Domain\Event\EmployeeHired;
use App\Employee\Domain\FirstName;
use App\Employee\Domain\HiredAt;
use App\Employee\Domain\LastName;
use App\Payroll\Domain\BonusName;
use App\Payroll\Domain\EmployeeId as PayrollEmployeeId;
use App\Payroll\Domain\Event\BonusSalaryCalculated;
use App\Payroll\Domain\ReadModel\PayrollListReadModel;
use App\Payroll\Domain\Salary;
use DateTimeImmutable;
use Faker\Factory;

class ListItemFacade
{
    public function __construct(private readonly PayrollListReadModel $readModel)
    {
    }

    public function withRandomListItem(): void
    {
        $this->withData(null, null, null);
    }

    public function withEmployeeData(
        FirstName $firstName,
        LastName $lastName,
    ): void
    {
        $this->withData($firstName, $lastName, null);
    }

    public function withDepartmentData(DepartmentName $departmentName): void
    {
        $this->withData(null, null, $departmentName);
    }

    public function clear(): void
    {
        $this->readModel->clearAll();
    }

    private function withData(?FirstName $firstName, ?LastName $lastName, ?DepartmentName $departmentName): void
    {
        $faker = Factory::create();

        $employeeId = UUID::random();
        if (!$firstName) {
            $firstName = new FirstName($faker->firstName());
        }
        if (!$lastName) {
            $lastName = new LastName($faker->lastName());
        }
        if (!$departmentName) {
            $departmentName = new DepartmentName($faker->randomElement(
                [
                    'IT',
                    'Sales',
                    'Engineering',
                    'HR',
                ]
            ));
        }

        $this->readModel->handleEmployeeHired(
            new EmployeeHired(
                UUID::random(),
                new EmployeeId($employeeId),
                new DepartmentId(UUID::random()),
                $departmentName,
                $firstName,
                $lastName,
                new HiredAt(new DateTimeImmutable()),
            )
        );

        $this->readModel->handleBonusSalaryCalculated(
            BonusSalaryCalculated::create(
                new PayrollEmployeeId($employeeId),
                new Salary(100),
                new Salary(100),
                new Salary(100),
                new BonusName('yearly'),
            )
        );
    }
}
