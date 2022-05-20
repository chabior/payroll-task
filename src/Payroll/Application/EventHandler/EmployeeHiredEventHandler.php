<?php

declare(strict_types=1);

namespace App\Payroll\Application\EventHandler;

use App\Common\EventBus;
use App\Common\EventHandler;
use App\Employee\Domain\Event\EmployeeHired;
use App\Payroll\Domain\DepartmentId;
use App\Payroll\Domain\Employee;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\EmployeeRepository;
use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\Policy\BonusSalaryPolicyFactory;
use App\Payroll\Domain\ReadModel\PayrollListReadModel;

class EmployeeHiredEventHandler implements EventHandler
{
    public function __construct(
        private readonly PayrollListReadModel $payrollListReadModel,
        private readonly EmployeeRepository $employeeRepository,
        private readonly BonusSalaryPolicyFactory $bonusSalaryPolicyFactory,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(EmployeeHired $employeeHired): void
    {
        $this->payrollListReadModel->handleEmployeeHired($employeeHired);

        $employee = Employee::create(
            new EmployeeId($employeeHired->employeeId->UUID),
            new DepartmentId($employeeHired->departmentId->department),
            new HiredAt($employeeHired->hiredAt->value)
        );

        $bonusSalaryPolicy = $this->bonusSalaryPolicyFactory->forEmployee($employee);
        $result = $employee->calculateCurrentBonusSalary($bonusSalaryPolicy);

        $this->employeeRepository->save(
            $employee
        );
        foreach ($result->events() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
