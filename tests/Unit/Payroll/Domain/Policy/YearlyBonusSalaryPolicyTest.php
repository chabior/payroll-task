<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Policy;

use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\Policy\Exception\PolicyException;
use App\Payroll\Domain\Policy\YearlyBonusSalaryPolicy;
use App\Payroll\Domain\Salary;
use App\Payroll\Domain\YearlyBonus;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class YearlyBonusSalaryPolicyTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestCalculate
     */
    public function testBonusCalculation(
        YearlyBonus $yearlyBonus,
        Salary      $baseSalary,
        HiredAt     $hiredAt,
        Salary      $expected
    ): void {
        $policy = new YearlyBonusSalaryPolicy($yearlyBonus);
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

    public function testExceptionIfTrieToCalculateSalaryBeforeEmployeeWasHired(): void
    {
        $this->expectException(PolicyException::class);
        $this->expectExceptionCode(1);

        $policy = new YearlyBonusSalaryPolicy(new YearlyBonus(100));
        $policy->calculate(
            new Salary(100),
            new HiredAt(Carbon::now()),
            Carbon::now()->subYears(6)
        );
    }

    public function dataProviderTestCalculate(): array
    {
        return [
            'task-description-case' => [
                '$yearlyBonus' => new YearlyBonus(10000),
                'baseSalary' => new Salary(100000),
                'hiredAt' => new HiredAt(Carbon::now()->subYears(15)),
                'expected' => new Salary(100000),
            ],
            'employee hired less than 10 years ago' => [
                '$yearlyBonus' => new YearlyBonus(10000),
                'baseSalary' => new Salary(100000),
                'hiredAt' => new HiredAt(Carbon::now()->subYears(5)),
                'expected' => new Salary(50000),
            ],
            'no bonus for someone hired at the same year' => [
                '$yearlyBonus' => new YearlyBonus(10000),
                'baseSalary' => new Salary(100000),
                'hiredAt' => new HiredAt(Carbon::now()),
                'expected' => new Salary(0),
            ],
        ];
    }
}
