<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain;

use App\Payroll\Domain\PercentageSalaryRatio;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PercentageSalaryRatioTest extends TestCase
{
    public function testRatioCantBeLowerThanZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new PercentageSalaryRatio(-0.5);
    }

    public function testRationCantBeLowerEqualZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new PercentageSalaryRatio(0);
    }
}
