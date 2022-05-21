<?php

declare(strict_types=1);

namespace App\Common\Filter;

use Doctrine\DBAL\Query\QueryBuilder;

abstract class Order
{
    abstract public function getProperty(): string;

    public function sort(OrderDirection $direction, QueryBuilder $queryBuilder): void
    {
        $queryBuilder->addOrderBy($this->getProperty(), $direction->value);
    }
}
