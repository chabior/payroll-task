<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain;

use App\Payroll\Domain\PercentageSalaryRatio;
use App\Payroll\Domain\Salary;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SalaryTest extends TestCase
{

    /**
     * @dataProvider dataProvider
     */
    public function testMultiplyByRatio(Salary $salary, PercentageSalaryRatio $ratio, Salary $expected): void
    {
        $newSalary = $salary->multiply($ratio);

        $this::assertEquals(
            $expected,
            $newSalary
        );
    }

    public function dataProvider(): array
    {
        return [
            [
                '$salary' => new Salary(100),
                '$ratio' => new PercentageSalaryRatio(0.010000011),
                '$expected' => new Salary(1)
            ],
            [
                '$salary' => new Salary(124),
                '$ratio' => new PercentageSalaryRatio(0.1),
                '$expected' => new Salary(12),
            ],
            [
                '$salary' => new Salary(177),
                '$ratio' => new PercentageSalaryRatio(0.1),
                '$expected' => new Salary(17),
            ],
        ];
    }

    public function testSalaryCantBeLowerThanZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Salary(-1);
    }
}
