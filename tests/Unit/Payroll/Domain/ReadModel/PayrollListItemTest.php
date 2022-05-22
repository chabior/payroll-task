<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\ReadModel;

use App\Payroll\Domain\ReadModel\PayrollListItem;
use PHPUnit\Framework\TestCase;

class PayrollListItemTest extends TestCase
{
    public function testSerialize(): void
    {
        $item = new PayrollListItem(
            'Paweł',
            'Chabierski',
            'IT',
            10000,
            0,
            10000,
            'yearly',
        );

        $serialized = $item->serialize();

        $this::assertEquals(
            [
                'Paweł',
                'Chabierski',
                'IT',
                100,
                0,
                100,
                'yearly',
            ],
            $serialized
        );
    }
}
