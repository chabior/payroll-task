<?php

declare(strict_types=1);

namespace App\Payroll\Domain\ReadModel;

class PayrollListItem
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $departmentName,
        public readonly int $baseSalary,
        public readonly int $bonusSalary,
        public readonly int $totalSalary,
        public readonly string $bonusName
    ) {
    }

    public function serialize(): array
    {
        return [
            $this->firstName,
            $this->lastName,
            $this->departmentName,
            number_format((float) bcdiv((string) $this->baseSalary, '100')),
            number_format((float) bcdiv((string) $this->bonusSalary, '100')),
            number_format((float) bcdiv((string) $this->totalSalary, '100')),
            $this->bonusName,
        ];
    }
}
