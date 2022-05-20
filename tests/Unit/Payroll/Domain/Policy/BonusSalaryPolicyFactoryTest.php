<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Policy;

use App\Common\UUID;
use App\Payroll\Domain\DepartmentBonusSalaryPolicyConfig;
use App\Payroll\Domain\DepartmentBonusSalaryPolicyRepository;
use App\Payroll\Domain\DepartmentId;
use App\Payroll\Domain\Employee;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\Policy\BonusSalaryPolicyFactory;
use App\Payroll\Domain\Policy\NoBonusPolicy;
use App\Payroll\Domain\Policy\YearlyBonusSalaryPolicy;
use App\Payroll\Domain\YearlyBonus;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class BonusSalaryPolicyFactoryTest extends TestCase
{
    public function testBonusSalaryIsCreatedForEmployeeWhenItIsDefined(): void
    {
        $departmentId = new DepartmentId(UUID::random());
        $expectedPolicy = new YearlyBonusSalaryPolicy(new YearlyBonus(10));

        $departmentBonusPolicyRepository = $this->createMock(DepartmentBonusSalaryPolicyRepository::class);
        $departmentBonusPolicyRepository->expects($this::once())->method('findForDepartment')->willReturn(
            new DepartmentBonusSalaryPolicyConfig(
                UUID::random(),
                $departmentId,
                $expectedPolicy
            )
        )
        ;

        $factory = new BonusSalaryPolicyFactory(
            $departmentBonusPolicyRepository
        );
        $policy = $factory->forEmployee(Employee::create(
            new EmployeeId(UUID::random()),
            $departmentId,
            new HiredAt(Carbon::now())
        ));

        $this::assertEquals($expectedPolicy, $policy);
    }

    public function testDefaultNoBonusPolicyIsReturnedWhenConfigNotExists(): void
    {
        $departmentId = new DepartmentId(UUID::random());

        $departmentBonusPolicyRepository = $this->createMock(DepartmentBonusSalaryPolicyRepository::class);
        $departmentBonusPolicyRepository->expects($this::once())->method('findForDepartment')->willReturn(
            null
        )
        ;

        $factory = new BonusSalaryPolicyFactory(
            $departmentBonusPolicyRepository
        );
        $policy = $factory->forEmployee(Employee::create(
            new EmployeeId(UUID::random()),
            $departmentId,
            new HiredAt(Carbon::now())
        ));

        $this::assertEquals(new NoBonusPolicy(), $policy);
    }
}
