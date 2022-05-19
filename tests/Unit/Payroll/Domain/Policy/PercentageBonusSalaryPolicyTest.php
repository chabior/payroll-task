<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Policy;

use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\PercentageSalaryRatio;
use App\Payroll\Domain\Policy\Exception\PolicyException;
use App\Payroll\Domain\Policy\PercentageBonusSalaryPolicy;
use App\Payroll\Domain\Salary;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PercentageBonusSalaryPolicyTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestSalaryCalculation
     */
    public function testSalaryCalculation(
        PercentageSalaryRatio $ratio,
        Salary $baseSalary,
        HiredAt $hiredAt,
        Salary $expected
    ): void {
        $policy = new PercentageBonusSalaryPolicy($ratio);
        $bonus = $policy->calculate(
            $baseSalary,
            $hiredAt,
            Carbon::now()
        );

        $this::assertEquals(
            $expected,
            $bonus
        );
    }

    public function dataProviderTestSalaryCalculation(): array
    {
        return [
            'task-description-case' => [
                'ratio' => new PercentageSalaryRatio(0.1),
                'baseSalary' => new Salary(110000),
                'hiredAt' => new HiredAt(Carbon::now()->subYears(5)),
                'expected' => new Salary(11000),
            ],
        ];
    }

    public function testExceptionIfTrieToCalculateSalaryBeforeEmployeeWasHired(): void
    {
        $this->expectException(PolicyException::class);
        $this->expectExceptionCode(1);

        $policy = new PercentageBonusSalaryPolicy(new PercentageSalaryRatio(0.1));
        $policy->calculate(
            new Salary(100),
            new HiredAt(Carbon::now()->subYears(5)),
            Carbon::now()->subYears(6)
        );
    }
}
