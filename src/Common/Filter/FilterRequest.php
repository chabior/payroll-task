<?php

declare(strict_types=1);

namespace App\Common\Filter;

use RuntimeException;

class FilterRequest
{
    /**
     * @var array<string, string>
     */
    private array $filters = [];

    /**
     * @var array<string, OrderDirection>
     */
    private array $sort = [];

    public function setFilter(string $key, string $value): FilterRequest
    {
        $obj = clone $this;
        $obj->filters[$key] = $value;
        return $obj;
    }

    public function getFilter(string $key): ?string
    {
        return $this->filters[$key] ?? null;
    }

    public function addSort(string $key, OrderDirection $direction): FilterRequest
    {
        $obj = clone $this;
        $obj->sort[$key] = $direction;
        return $obj;
    }

    public function getSort(string $key): ?OrderDirection
    {
        return $this->sort[$key] ?? null;
    }

    /**
     * @param array<string> $allowedFilters
     * @param array<string> $allowedOrder
     * @return void
     */
    public function validate(array $allowedFilters, array $allowedOrder): void
    {
        foreach (array_keys($this->filters) as $filter) {
            if (!in_array($filter, $allowedFilters, true)) {
                throw new RuntimeException(
                    sprintf(
                        'Filter %s is not allowed',
                        $filter
                    )
                );
            }
        }

        foreach (array_keys($this->sort) as $order) {
            if (!in_array($order, $allowedOrder, true)) {
                throw new RuntimeException(
                    sprintf(
                        'Order %s is not allowed',
                        $order
                    )
                );
            }
        }
    }
}
