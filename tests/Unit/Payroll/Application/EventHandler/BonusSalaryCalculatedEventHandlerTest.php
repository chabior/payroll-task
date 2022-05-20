<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Application\EventHandler;

use App\Common\UUID;
use App\Payroll\Application\EventHandler\BonusSalaryCalculatedEventHandler;
use App\Payroll\Domain\BonusName;
use App\Payroll\Domain\EmployeeId;
use App\Payroll\Domain\Event\BonusSalaryCalculated;
use App\Payroll\Domain\ReadModel\PayrollListReadModel;
use App\Payroll\Domain\Salary;
use PHPUnit\Framework\TestCase;

class BonusSalaryCalculatedEventHandlerTest extends TestCase
{
    public function testReadModelIsRefreshed(): void
    {
        $bonusSalaryCalculated = BonusSalaryCalculated::create(
            new EmployeeId(UUID::random()),
            new Salary(100),
            new Salary(100),
            new Salary(100),
            new BonusName('no bonus'),
        );

        $payrollListReadModel = $this->createMock(PayrollListReadModel::class);
        $payrollListReadModel->expects($this::once())->method('handleBonusSalaryCalculated')->with($bonusSalaryCalculated);

        $handler = new BonusSalaryCalculatedEventHandler(
            $payrollListReadModel
        );

        $handler->__invoke(
            $bonusSalaryCalculated
        );
    }
}
