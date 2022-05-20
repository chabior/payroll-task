<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain;

use App\Payroll\Domain\HiredAt;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class HiredAtTest extends TestCase
{
    public function testHiredAtBefore(): void
    {
        $hiredAt = new HiredAt(Carbon::now());
        $result = $hiredAt->before(Carbon::now()->subYear());

        $this::assertTrue($result);
    }

    public function testHiredAtAfter(): void
    {
        $hiredAt = new HiredAt(Carbon::now());
        $result = $hiredAt->before(Carbon::now()->addYear());

        $this::assertFalse($result);
    }

    public function testHiredAtAfterIsFalseWhenDatesAreEquals(): void
    {
        $date = Carbon::now();
        $hiredAt = new HiredAt($date);
        $result = $hiredAt->before($date);

        $this::assertFalse($result);
    }
}
