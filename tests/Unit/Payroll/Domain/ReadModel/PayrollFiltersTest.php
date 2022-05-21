<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\ReadModel;

use App\Common\Filter\FilterRequest;
use App\Common\Filter\OrderDirection;
use App\Payroll\Domain\ReadModel\PayrollFilters;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

class PayrollFiltersTest extends TestCase
{
    /**
     * @dataProvider dataProviderForFiltersTests
     */
    public function testListCanBeFilteredBy(string $property, string $value): void
    {
        $request = new FilterRequest();
        $request = $request->setFilter($property, $value);

        $filters = new PayrollFilters($request);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('expr')->willReturn(new ExpressionBuilder($this->createMock(Connection::class)));

        $queryBuilder->expects($this::once())->method('andWhere')->with(
            sprintf('%s LIKE :%s', $property, $property)
        );
        $queryBuilder->expects($this::once())->method('setParameter')->with(
            $property,
            sprintf('%%%s%%', $value)
        );

        $filters->filter(
            $queryBuilder
        );
    }

    public function dataProviderForFiltersTests(): array
    {
        return [
            'first_name' => [
                '$property' => 'first_name',
                '$value' => 'Paweł',
            ],
            'last_name' => [
                '$property' => 'last_name',
                '$value' => 'Chabierski',
            ],
            'department_name' => [
                '$property' => 'department_name',
                '$value' => 'IT',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSortTests
     */
    public function testListCanBeSortedBy(string $property, OrderDirection $orderDirection): void
    {
        $request = new FilterRequest();
        $request = $request->addSort($property, $orderDirection);

        $filters = new PayrollFilters($request);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('expr')->willReturn(new ExpressionBuilder($this->createMock(Connection::class)));

        $queryBuilder->expects($this::once())->method('addOrderBy')->with(
            $property,
            $orderDirection->value
        );

        $filters->filter(
            $queryBuilder
        );
    }

    public function dataProviderForSortTests(): array
    {
        return [
            'first_name_asc' => [
                '$property' => 'first_name',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'first_name_desc' => [
                '$property' => 'first_name',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'last_name_asc' => [
                '$property' => 'last_name',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'last_name_desc' => [
                '$property' => 'last_name',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'department_name_asc' => [
                '$property' => 'department_name',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'department_name_desc' => [
                '$property' => 'department_name',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'base_salary_asc' => [
                '$property' => 'base_salary',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'base_salary_desc' => [
                '$property' => 'base_salary',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'bonus_salary_asc' => [
                '$property' => 'base_salary',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'bonus_salary_desc' => [
                '$property' => 'base_salary',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'total_salary_asc' => [
                '$property' => 'total_salary',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'total_salary_desc' => [
                '$property' => 'total_salary',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'bonus_name_asc' => [
                '$property' => 'bonus_name',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'bonus_name_desc' => [
                '$property' => 'bonus_name',
                '$orderDirection' => OrderDirection::DESC,
            ],
        ];
    }
}
