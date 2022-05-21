<?php

declare(strict_types=1);

namespace App\Payroll\Infrastructure\Doctrine;

use App\Payroll\Domain\Policy\BonusSalaryPolicy;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;
use InvalidArgumentException;

class BonusSalaryPolicyType extends TextType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?BonusSalaryPolicy
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException('Only string can be converted to BonusSalaryPolicy');
        }

        /**
         * @var BonusSalaryPolicy $unserialize
         */
        $unserialize = unserialize(stripslashes($value));
        if (!$unserialize instanceof BonusSalaryPolicy) {
            throw new InvalidArgumentException('Invalid unserialize value');
        }

        return $unserialize;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value instanceof BonusSalaryPolicy && $value !== null) {
            throw new \InvalidArgumentException();
        }

        if ($value === null) {
            return null;
        }

        return addslashes(serialize($value));
    }
}
