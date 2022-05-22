<?php

declare(strict_types=1);

namespace App\Payroll\UI\CLI;

use Carbon\Carbon;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateTaskDescriptionDataCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('payroll:create-task-description-data-command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $application = $this->getApplication();
        if (!$application) {
            $style->error('No application');
            return 1;
        }

        $this->executeCommand(
            $application->get('department:create-department'),
            [
                'HR',
            ]
        );
        $this->executeCommand(
            $application->get('department:create-department'),
            [
                'Dział obsługi klienta',
            ]
        );
        $this->executeCommand(
            $application->get('payroll:create-bonus-salary-policy-for-department'),
            [
                0,
                1,
                10000,
            ]
        );
        $this->executeCommand(
            $application->get('payroll:create-bonus-salary-policy-for-department'),
            [
                1,
                0,
                0.1,
            ]
        );
        $this->executeCommand(
            $application->get('employee:hire-employee'),
            [
                'Adam',
                'Kowalski',
                0,
                Carbon::now()->subYears(15)->format('Y-m-d'),
            ]
        );
        $this->executeCommand(
            $application->get('employee:hire-employee'),
            [
                'Ania',
                'Nowak',
                1,
                Carbon::now()->subYears(5)->format('Y-m-d'),
            ]
        );

        return 0;
    }

    /**
     * @param array<string|int|float> $inputs
     * @return resource
     */
    private function createStream(array $inputs)
    {
        $stream = fopen('php://memory', 'r+', false);

        foreach ($inputs as $input) {
            fwrite($stream, $input . \PHP_EOL);
        }

        rewind($stream);

        return $stream;
    }

    private function createDepartment(Application $application, OutputInterface $output, string $departmentName): void
    {
        $command = $application->get('department:create-department');
        $arrayInput = new ArrayInput([]);
        $arrayInput->setStream(
            $this->createStream(
                [
                    $departmentName,
                ]
            )
        );
        $command->run(
            $arrayInput,
            $output
        );
    }

    /**
     * @param array<string|int|float> $inputs
     */
    private function executeCommand(Command $command, array $inputs): void
    {
        $arrayInput = new ArrayInput([]);
        $arrayInput->setStream(
            $this->createStream(
                $inputs,
            )
        );

        $command->run(
            $arrayInput,
            new NullOutput()
        );
    }
}
