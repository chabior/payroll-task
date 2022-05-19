<?php

declare(strict_types=1);

namespace App\Tests\Integration\Payroll\Domain\ReadModel;

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
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PayrollListReadModelTest extends KernelTestCase
{
    private PayrollListReadModel $readModel;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->readModel = $this->getObj();
    }

    public function testHandleEmployeeHired(): void
    {
        $employeeId = new EmployeeId(UUID::random());
        $this->readModel->handleEmployeeHired(
            new EmployeeHired(
                UUID::random(),
                $employeeId,
                new DepartmentId(UUID::random()),
                new DepartmentName('Department'),
                new FirstName('Ania'),
                new LastName('Kowalska'),
                new HiredAt(new DateTimeImmutable()),
            )
        );

        $result = $this->readModel->hasRecordFor(new PayrollEmployeeId($employeeId->UUID));
        $this::assertTrue($result);
    }

    public function testHandleBonusSalaryCalculated(): void
    {
        self::bootKernel();

        $employeeId = new PayrollEmployeeId(UUID::random());

        $this->readModel->handleEmployeeHired(
            new EmployeeHired(
                UUID::random(),
                new EmployeeId($employeeId->UUID),
                new DepartmentId(UUID::random()),
                new DepartmentName('Department'),
                new FirstName('Ania'),
                new LastName('Kowalska'),
                new HiredAt(new DateTimeImmutable()),
            )
        );

        $this->readModel->handleBonusSalaryCalculated(
            BonusSalaryCalculated::create(
                $employeeId,
                new Salary(100),
                new Salary(100),
                new Salary(100),
                new BonusName('yearly'),
            )
        );

        $result = $this->readModel->hasRecordFor(new PayrollEmployeeId($employeeId->UUID));
        $this::assertTrue($result);
    }

    public function testExceptionWhenBonusSalaryHappensForNotExistingEmployee(): void
    {
        $this->expectException(RuntimeException::class);

        $employeeId = new PayrollEmployeeId(UUID::random());

        $this->readModel->handleBonusSalaryCalculated(
            BonusSalaryCalculated::create(
                $employeeId,
                new Salary(100),
                new Salary(100),
                new Salary(100),
                new BonusName('yearly'),
            )
        );
    }

    private function getObj(): PayrollListReadModel
    {
        return self::getContainer()->get(PayrollListReadModel::class);
    }
}
