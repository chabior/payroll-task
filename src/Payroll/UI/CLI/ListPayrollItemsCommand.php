<?php

declare(strict_types=1);

namespace App\Payroll\UI\CLI;

use App\Common\Filter\Filter;
use App\Common\Filter\FilterRequest;
use App\Common\Filter\Order;
use App\Common\Filter\OrderDirection;
use App\Payroll\Domain\ReadModel\PayrollFilters;
use App\Payroll\Domain\ReadModel\PayrollListReadModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListPayrollItemsCommand extends Command
{
    private const NO_FILTERS_OPTION = 'No Filters';
    private const NO_ORDER_OPTION = 'No order';

    public function __construct(private readonly PayrollListReadModel $payrollListReadModel)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('payroll:list-items');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $request = new FilterRequest();
        $filters = new PayrollFilters();

        /**
         * @var string $filter
         */
        $filter = $style->choice(
            'Filter by',
            array_merge(
                [self::NO_FILTERS_OPTION],
                array_map(static function (Filter $filter): string {
                    return $filter->getProperty();
                }, $filters->getAllowedFilters())
            )
        );

        if ($filter !== self::NO_FILTERS_OPTION) {
            /**
             * @var string $value
             */
            $value = $style->ask('Value');
            $request = $request->setFilter($filter, $value);
        }

        /**
         * @var string $orderBy
         */
        $orderBy = $style->choice(
            'Order by',
            array_merge(
                [self::NO_ORDER_OPTION],
                array_map(static fn(Order $order): string => $order->getProperty(), $filters->getAllowedOrder())
            )
        );
        if ($orderBy !== self::NO_ORDER_OPTION) {
            /**
             * @var string $direction
             */
            $direction = $style->choice(
                'direction',
                array_map(
                    static fn(OrderDirection $orderDirection): string => $orderDirection->value,
                    OrderDirection::cases()
                )
            );
            $request = $request->addSort($orderBy, OrderDirection::from($direction));
        }

        $items = $this->payrollListReadModel->all(
            $filters->withRequest($request)
        );

        if (count($items) === 0) {
            $style->info('Items not found');
            return 0;
        }

        $data = [];
        foreach ($items as $item) {
            $data[] = $item->serialize();
        }

        $style->table(
            [
                'First Name',
                'Last Name',
                'Department Name',
                'Base Salary',
                'Bonus Salary',
                'Total Salary',
                'Bonus Name',
            ],
            $data
        );

        return 0;
    }
}
