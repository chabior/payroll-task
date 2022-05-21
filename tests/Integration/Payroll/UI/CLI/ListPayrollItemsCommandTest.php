<?php

declare(strict_types=1);

namespace App\Tests\Integration\Payroll\UI\CLI;

use App\Common\Filter\FilterRequest;
use App\Common\Filter\OrderDirection;
use App\Payroll\Domain\ReadModel\PayrollFilters;
use App\Payroll\Domain\ReadModel\PayrollListItem;
use App\Payroll\Domain\ReadModel\PayrollListReadModel;
use App\Payroll\UI\CLI\ListPayrollItemsCommand;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ListPayrollItemsCommandTest extends KernelTestCase
{
    private PayrollListReadModel|MockObject $payrollListReadModel;
    private CommandTester $commandTester;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $this->payrollListReadModel = $this->createMock(PayrollListReadModel::class);

        $application->add(
            new ListPayrollItemsCommand(
                $this->payrollListReadModel
            )
        );

        $command = $application->find('payroll:list-items');
        $this->commandTester = new CommandTester($command);
    }

    public function testListCanBeFilteredByFirstNameAndReturnData(): void
    {
        $this->payrollListReadModel->expects($this::once())->method('all')->with(
            new PayrollFilters((new FilterRequest())->setFilter('first_name', 'Paweł'))
        )->willReturn(
            $this->getItems()
        )
        ;

        $this->commandTester->setInputs([1, 'Paweł', 0]);

        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful();

        $display = $this->commandTester->getDisplay();

        $this::assertStringContainsString('Paweł', $display);
    }

    public function testListCanBeFilteredByLastNameAndReturnData(): void
    {
        $this->payrollListReadModel->expects($this::once())->method('all')->with(
            new PayrollFilters((new FilterRequest())->setFilter('last_name', 'Chabierski'))
        )->willReturn(
            $this->getItems()
        )
        ;

        $this->commandTester->setInputs([2, 'Chabierski', 0]);

        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful();

        $display = $this->commandTester->getDisplay();

        $this::assertStringContainsString('Chabierski', $display);
    }

    public function testListCanBeFilteredByDepartmentNameAndReturnData(): void
    {
        $this->payrollListReadModel->expects($this::once())->method('all')->with(
            new PayrollFilters((new FilterRequest())->setFilter('department_name', 'IT'))
        )->willReturn(
            $this->getItems()
        )
        ;

        $this->commandTester->setInputs([3, 'IT', 0]);

        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful();

        $display = $this->commandTester->getDisplay();

        $this::assertStringContainsString('IT', $display);
    }

    /**
     * @dataProvider dataProviderSorted
     */
    public function testListCanBeSorted(
        array $inputs,
        string $sortKey,
        OrderDirection $orderDirection
    ): void {
        $this->payrollListReadModel->expects($this::once())->method('all')->with(
            new PayrollFilters((new FilterRequest())->addSort($sortKey, $orderDirection))
        )->willReturn(
            $this->getItems()
        )
        ;

        $this->commandTester->setInputs($inputs);

        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful();
    }

    public function dataProviderSorted(): array
    {
        return [
            'first name asc' => [
                '$inputs' => [0, 1, 0],
                '$sortKey' => 'first_name',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'first name desc' => [
                '$inputs' => [0, 1, 1],
                '$sortKey' => 'first_name',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'last name asc' => [
                '$inputs' => [0, 2, 0],
                '$sortKey' => 'last_name',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'last name desc' => [
                '$inputs' => [0, 2, 1],
                '$sortKey' => 'last_name',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'department_name asc' => [
                '$inputs' => [0, 3, 0],
                '$sortKey' => 'department_name',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'department_name desc' => [
                '$inputs' => [0, 3, 1],
                '$sortKey' => 'department_name',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'base_salary asc' => [
                '$inputs' => [0, 4, 0],
                '$sortKey' => 'base_salary',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'base_salary desc' => [
                '$inputs' => [0, 4, 1],
                '$sortKey' => 'base_salary',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'bonus_salary asc' => [
                '$inputs' => [0, 5, 0],
                '$sortKey' => 'bonus_salary',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'bonus_salary desc' => [
                '$inputs' => [0, 5, 1],
                '$sortKey' => 'bonus_salary',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'total_salary asc' => [
                '$inputs' => [0, 6, 0],
                '$sortKey' => 'total_salary',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'total_salary desc' => [
                '$inputs' => [0, 6, 1],
                '$sortKey' => 'total_salary',
                '$orderDirection' => OrderDirection::DESC,
            ],
            'bonus_name asc' => [
                '$inputs' => [0, 7, 0],
                '$sortKey' => 'bonus_name',
                '$orderDirection' => OrderDirection::ASC,
            ],
            'bonus_name desc' => [
                '$inputs' => [0, 7, 1],
                '$sortKey' => 'bonus_name',
                '$orderDirection' => OrderDirection::DESC,
            ],
        ];
    }

    private function getItems(): array
    {
        return [
            new PayrollListItem(
                'Paweł',
                'Chabierski',
                'IT',
                100,
                100,
                200,
                'yearly',
            ),
        ];
    }
}
