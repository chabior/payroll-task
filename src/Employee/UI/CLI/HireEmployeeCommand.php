<?php

declare(strict_types=1);

namespace App\Employee\UI\CLI;

use App\Common\EventBus;
use App\Common\UUID;
use App\Department\Domain\Department;
use App\Department\Domain\DepartmentName as DepartmentDepartmentName;
use App\Department\Domain\DepartmentRepository;
use App\Employee\Domain\DepartmentId;
use App\Employee\Domain\DepartmentName;
use App\Employee\Domain\EmployeeId;
use App\Employee\Domain\Event\EmployeeHired;
use App\Employee\Domain\FirstName;
use App\Employee\Domain\HiredAt;
use App\Employee\Domain\LastName;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HireEmployeeCommand extends Command
{
    public function __construct(
        private readonly EventBus $eventBus,
        private readonly DepartmentRepository $departmentRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('employee:hire-employee');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $firstName = $style->ask(
            'First name'
        );
        $lastName = $style->ask(
            'Last name'
        );
        $departmentName = $style->choice(
            'Department ID',
            array_map(static function (Department $department) {
                return $department->getName()->name;
            }, $this->departmentRepository->all())
        );

        $department = $this->departmentRepository->findOneByName(new DepartmentDepartmentName($departmentName));
        if (!$department) {
            $style->error('Invalid department');
            return 1;
        }

        $hiredAt = $style->ask(
            'Hired At (in Y-m-d format)',
            null,
            static function (string $value): DateTimeInterface {
                $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);
                if ($date === false) {
                    throw new \RuntimeException(
                        'Invalid format. Only Y-m-d is accepted'
                    );
                }

                return $date;
            }
        );

        $this->eventBus->dispatch(
            new EmployeeHired(
                UUID::random(),
                new EmployeeId(UUID::random()),
                new DepartmentId(new UUID($department->getId()->__toString())),
                new DepartmentName($department->getName()->name),
                new FirstName($firstName),
                new LastName($lastName),
                new HiredAt($hiredAt)
            )
        );

        return 0;
    }
}
