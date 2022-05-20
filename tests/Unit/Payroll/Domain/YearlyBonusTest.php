<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain;

use App\Payroll\Domain\YearlyBonus;
use PHPUnit\Framework\TestCase;

class YearlyBonusTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testForYears(YearlyBonus $bonus, int $years, int $expected): void
    {
        $result = $bonus->forYears($years);

        $this::assertEquals($expected, $result);
    }

    public function dataProvider(): array
    {
        return [
            [
                '$bonus' => new YearlyBonus(100),
                '$years' => 2,
                '$expected' => 200,
            ],
            [
                '$bonus' => new YearlyBonus(100),
                '$years' => 0,
                '$expected' => 0,
            ],
            [
                '$bonus' => new YearlyBonus(100),
                '$years' => -1,
                '$expected' => 0,
            ]
        ];
    }
}
