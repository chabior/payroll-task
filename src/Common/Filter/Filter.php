<?php

declare(strict_types=1);

namespace App\Common\Filter;

use Doctrine\DBAL\Query\QueryBuilder;

abstract class Filter
{
    abstract public function getProperty(): string;

    abstract public function filter(string $value, QueryBuilder $queryBuilder): void;
}
