<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Application\EventHandler;

use App\Common\EventBus;
use App\Common\UUID;
use App\Employee\Domain\DepartmentId;
use App\Employee\Domain\DepartmentName;
use App\Employee\Domain\EmployeeId;
use App\Employee\Domain\Event\EmployeeHired;
use App\Employee\Domain\FirstName;
use App\Employee\Domain\HiredAt;
use App\Employee\Domain\LastName;
use App\Payroll\Application\EventHandler\EmployeeHiredEventHandler;
use App\Payroll\Domain\Employee;
use App\Payroll\Domain\EmployeeRepository;
use App\Payroll\Domain\Event\BonusSalaryCalculated;
use App\Payroll\Domain\Policy\BonusSalaryPolicyFactory;
use App\Payroll\Domain\Policy\NoBonusPolicy;
use App\Payroll\Domain\ReadModel\PayrollListReadModel;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmployeeHiredEventHandlerTest extends KernelTestCase
{
    public function testNewBonusSalaryForEmployeeIsCalculated(): void
    {
        self::bootKernel();

        $employeeId = UUID::random();
        $departmentId = UUID::random();
        $hiredAt = Carbon::now();
        $employeeHired = new EmployeeHired(
            UUID::random(),
            new EmployeeId($employeeId),
            new DepartmentId($departmentId),
            new DepartmentName('IT'),
            new FirstName('Ania'),
            new LastName('Kowalska'),
            new HiredAt($hiredAt)
        );

        $payrollListReadModel = $this->createMock(PayrollListReadModel::class);
        $payrollListReadModel->expects($this::once())->method('handleEmployeeHired')->with($employeeHired);

        $employeeRepository = $this->createMock(EmployeeRepository::class);
        $employee = Employee::create(
            new \App\Payroll\Domain\EmployeeId($employeeId),
            new \App\Payroll\Domain\DepartmentId($departmentId),
            new \App\Payroll\Domain\HiredAt($hiredAt),
        );
        $employeeRepository->expects($this::once())->method('save')->with(
            $employee
        );

        $bonusSalaryPolicyFactory = $this->createMock(BonusSalaryPolicyFactory::class);
        $bonusSalaryPolicyFactory->expects($this::once())->method('forEmployee')->with($employee)->willReturn(new NoBonusPolicy());

        $eventBus = $this->createMock(EventBus::class);
        $eventBus->expects($this::once())->method('dispatch')->with(
            $this::callback(
                static function (BonusSalaryCalculated $bonusSalaryCalculated) use($employeeId) : bool {
                    return $bonusSalaryCalculated->getEmployeeId()->UUID->isEqual($employeeId);
                }
            )
        );

        $eventHandler = new EmployeeHiredEventHandler(
            $payrollListReadModel,
            $employeeRepository,
            $bonusSalaryPolicyFactory,
            $eventBus,
        );
        $eventHandler->__invoke(
            $employeeHired
        );
    }
}
