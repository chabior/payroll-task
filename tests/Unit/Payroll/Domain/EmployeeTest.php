<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain;

use App\Common\UUID;
use App\Payroll\Domain\DepartmentId;
use App\Payroll\Domain\Employee;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\Event\BonusSalaryCalculated;
use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\Policy\YearlyBonusSalaryPolicy;
use App\Payroll\Domain\Salary;
use App\Payroll\Domain\YearlyBonus;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    public function testBonusSalaryCanBeCalculated(): void
    {
        $employee = new Employee(
            new EmployeeId(UUID::random()),
            new Salary(100000),
            new Salary(0),
            new DepartmentId(UUID::random()),
            new HiredAt(Carbon::now()->subYears(2))
        );

        $result = $employee->calculateCurrentBonusSalary(
            new YearlyBonusSalaryPolicy(new YearlyBonus(10000))
        );

        $this::assertTrue($result->isSuccessful());

        $this::assertCount(1, $result->events());
        $domainEvent = $result->events()[0];

        $this::assertInstanceOf(BonusSalaryCalculated::class, $domainEvent);
        $this::assertEquals(
            new Salary(20000),
            $domainEvent->getBonusSalary()
        );
        $this::assertEquals(
            new Salary(120000),
            $domainEvent->getTotalSalary()
        );
    }
}
