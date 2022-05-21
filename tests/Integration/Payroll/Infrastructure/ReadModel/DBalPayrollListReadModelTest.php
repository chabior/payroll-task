<?php

declare(strict_types=1);

namespace App\Tests\Integration\Payroll\Infrastructure\ReadModel;

use App\Common\Filter\FilterRequest;
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
use App\Payroll\Domain\ReadModel\PayrollFilters;
use App\Payroll\Domain\ReadModel\PayrollListItem;
use App\Payroll\Domain\Salary;
use App\Payroll\Infrastructure\DbalPayrollListReadModel;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DBalPayrollListReadModelTest extends KernelTestCase
{
    private DbalPayrollListReadModel $readModel;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->readModel = $this->getObj();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        self::getContainer()->get(Connection::class)->executeStatement('DELETE FROM payroll_list');
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

    public function testAllRecordsCanBeLoaded(): void
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

        $results = $this->readModel->all(new PayrollFilters(new FilterRequest()));

        $this::assertCount(1, $results);
        foreach ($results as $result) {
            $this::assertInstanceOf(PayrollListItem::class, $result);
        }
    }

    private function getObj(): DbalPayrollListReadModel
    {
        return self::getContainer()->get(DbalPayrollListReadModel::class);
    }
}
