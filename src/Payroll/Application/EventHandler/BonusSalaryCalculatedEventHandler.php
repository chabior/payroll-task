<?php

declare(strict_types=1);

namespace App\Payroll\Application\EventHandler;

use App\Common\EventHandler;
use App\Payroll\Domain\Event\BonusSalaryCalculated;
use App\Payroll\Domain\ReadModel\PayrollListReadModel;

class BonusSalaryCalculatedEventHandler implements EventHandler
{
    public function __construct(private readonly PayrollListReadModel $payrollListReadModel)
    {
    }

    public function __invoke(BonusSalaryCalculated $bonusSalaryCalculated): void
    {
        $this->payrollListReadModel->handleBonusSalaryCalculated($bonusSalaryCalculated);
    }
}
