<?php

declare(strict_types=1);

namespace App\Payroll\Infrastructure\Doctrine;

use App\Payroll\Domain\Policy\BonusSalaryPolicy;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

class BonusSalaryPolicyType extends TextType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?BonusSalaryPolicy
    {
        if ($value === null) {
            return null;
        }

        return unserialize($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value instanceof BonusSalaryPolicy && $value !== null) {
            throw new \InvalidArgumentException();
        }

        if ($value === null) {
            return null;
        }

        return serialize($value);
    }
}
