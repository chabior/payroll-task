<?php

declare(strict_types=1);

namespace App\Common\Filter;

use Doctrine\DBAL\Query\QueryBuilder;
use RuntimeException;

abstract class Filters
{
    public function __construct(private ?FilterRequest $request = null)
    {
        $filters = $this->getAllowedFiltersProperties();
        $duplicatedFilters = $this->getDuplicates($filters);
        if (count($duplicatedFilters) > 0) {
            throw new RuntimeException(
                sprintf(
                    'Filters with duplicated properties %s',
                    implode(', ', $duplicatedFilters)
                )
            );
        }

        $orders = $this->getAllowedOrderProperties();
        $duplicatedOrders = $this->getDuplicates($orders);
        if (count($duplicatedOrders) > 0) {
            throw new RuntimeException(
                sprintf(
                    'Orders with duplicated properties %s',
                    implode(', ', $duplicatedOrders)
                )
            );
        }

        $this->request?->validate(
            $filters,
            $orders,
        );
    }

    /**
     * @return Filter[]
     */
    abstract public function getAllowedFilters(): array;

    /**
     * @return Order[]
     */
    abstract public function getAllowedOrder(): array;

    public function withRequest(FilterRequest $request): static
    {
        $request->validate(
            $this->getAllowedFiltersProperties(),
            $this->getAllowedOrderProperties(),
        );

        $obj = clone $this;
        $obj->request = $request;
        return $obj;
    }

    public function filter(QueryBuilder $queryBuilder): void
    {
        if (!$this->request) {
            return;
        }

        $this->applyFilters($queryBuilder);

        $this->applyOrders($queryBuilder);
    }

    /**
     * @param array<string> $values
     * @return array<string>
     */
    private function getDuplicates(array $values): array
    {
        $duplicates = [];
        foreach (array_count_values($values) as $val => $quantity) {
            if ($quantity > 1) {
                $duplicates[] = $val;
            }
        }

        return $duplicates;
    }

    private function applyFilters(QueryBuilder $queryBuilder): void
    {
        foreach ($this->getAllowedFilters() as $filter) {
            $property = $filter->getProperty();
            $value = $this->request?->getFilter($property);
            if ($value === null) {
                continue;
            }

            $filter->filter($value, $queryBuilder);
        }
    }

    private function applyOrders(QueryBuilder $queryBuilder): void
    {
        foreach ($this->getAllowedOrder() as $order) {
            $property = $order->getProperty();
            $direction = $this->request?->getSort($property);
            if ($direction === null) {
                continue;
            }

            $order->sort($direction, $queryBuilder);
        }
    }

    /**
     * @return array<string>
     */
    private function getAllowedFiltersProperties(): array
    {
        return array_map(static fn(Filter $filter): string => $filter->getProperty(), $this->getAllowedFilters());
    }

    /**
     * @return array<string>
     */
    private function getAllowedOrderProperties(): array
    {
        return array_map(static fn(Order $order): string => $order->getProperty(), $this->getAllowedOrder());
    }

    public function __clone()
    {
        if ($this->request) {
            $this->request = clone $this->request;
        }
    }
}
