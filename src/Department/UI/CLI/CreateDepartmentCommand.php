<?php

declare(strict_types=1);

namespace App\Department\UI\CLI;

use App\Common\UUID;
use App\Department\Domain\Department;
use App\Department\Domain\DepartmentId;
use App\Department\Domain\DepartmentName;
use App\Department\Domain\DepartmentRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateDepartmentCommand extends Command
{
    public function __construct(private readonly DepartmentRepository $departmentRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('department:create-department');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $departmentName = (string) $style->ask('Department name');

        $this->departmentRepository->save(
            new Department(
                new DepartmentId(UUID::random()),
                new DepartmentName($departmentName)
            )
        );

        return 0;
    }
}
