<?php

declare(strict_types=1);

namespace App\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use InvalidArgumentException;

class UUIDType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UUID
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException('Only string can be converted to UUID');
        }

        return new UUID($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if (!$value instanceof UUID && $value !== null) {
            throw new \InvalidArgumentException();
        }

        if ($value === null) {
            return null;
        }

        return $value->__toString();
    }
}
