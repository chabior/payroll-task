<?php

declare(strict_types=1);

namespace App\Common\Filter;

use Doctrine\DBAL\Query\QueryBuilder;

abstract class StringFilter extends Filter
{
    public function filter(string $value, QueryBuilder $queryBuilder): void
    {
        $parameterName = sprintf(':%s', $this->getProperty());
        $queryBuilder->andWhere(
            $queryBuilder->expr()->like(
                $this->getProperty(),
                $parameterName
            )
        );
        $queryBuilder->setParameter($this->getProperty(), sprintf('%%%s%%', $value));
    }
}
