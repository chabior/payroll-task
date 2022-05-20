<?php

declare(strict_types=1);

namespace App\Tests\Integration\Payroll\Infrastructure\Doctrine;

use App\Common\UUID;
use App\Payroll\Domain\DepartmentId;
use App\Payroll\Domain\Employee;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\Policy\Exception\NotUniqueEmployeeException;
use App\Payroll\Infrastructure\Doctrine\DoctrineEmployeeRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineEmployeeRepositoryTest extends KernelTestCase
{
    private DoctrineEmployeeRepository $repo;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->repo = self::getContainer()->get(DoctrineEmployeeRepository::class);
    }

    public function testEmployeeCanBeSaved(): void
    {
        $employeeId = new EmployeeId(UUID::random());
        $employee = Employee::create(
            $employeeId,
            new DepartmentId(UUID::random()),
            new HiredAt(Carbon::now()),
        );

        $this->repo->save($employee);

        $employeeLoaded = $this->repo->find($employeeId->UUID);

        $this::assertNotNull($employeeLoaded);
    }

    public function testMultipleEmployeesWithSameIdCanBeSaved(): void
    {
        $this->expectException(NotUniqueEmployeeException::class);

        $employeeId = new EmployeeId(UUID::random());
        $employee = Employee::create(
            $employeeId,
            new DepartmentId(UUID::random()),
            new HiredAt(Carbon::now()),
        );

        $this->repo->save($employee);

        $employee = Employee::create(
            $employeeId,
            new DepartmentId(UUID::random()),
            new HiredAt(Carbon::now()),
        );
        $this->repo->save($employee);
    }
}
