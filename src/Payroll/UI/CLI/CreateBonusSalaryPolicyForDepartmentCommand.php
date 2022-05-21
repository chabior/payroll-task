<?php

declare(strict_types=1);

namespace App\Payroll\UI\CLI;

use App\Common\UUID;
use App\Department\Domain\Department;
use App\Department\Domain\DepartmentName as DepartmentDepartmentName;
use App\Department\Domain\DepartmentRepository;
use App\Payroll\Domain\DepartmentBonusSalaryPolicyConfig;
use App\Payroll\Domain\DepartmentBonusSalaryPolicyRepository;
use App\Payroll\Domain\DepartmentId;
use App\Payroll\Domain\PercentageSalaryRatio;
use App\Payroll\Domain\Policy\PercentageBonusSalaryPolicy;
use App\Payroll\Domain\Policy\YearlyBonusSalaryPolicy;
use App\Payroll\Domain\YearlyBonus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateBonusSalaryPolicyForDepartmentCommand extends Command
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
        private readonly DepartmentBonusSalaryPolicyRepository $departmentBonusSalaryPolicyRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('payroll:create-bonus-salary-policy-for-department');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $departmentName = (string) $style->choice(
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

        /**
         * @var class-string $policy
         */
        $policy = $style->choice(
            'Bonus salary policy',
            [
                PercentageBonusSalaryPolicy::class,
                YearlyBonusSalaryPolicy::class,
            ]
        );

        switch ($policy) {
            case PercentageBonusSalaryPolicy::class:
                /**
                 * @var PercentageSalaryRatio $ratio
                 */
                $ratio = $style->ask(
                    'ratio - float value bigger than 0',
                    null,
                    static function (string $value) {
                        return new PercentageSalaryRatio((float) $value);
                    }
                );
                $bonusPolicy = new PercentageBonusSalaryPolicy($ratio);
                break;
            case YearlyBonusSalaryPolicy::class:
                /**
                 * @var YearlyBonus $bonus
                 */
                $bonus = $style->ask(
                    'Fixed value per every year of work',
                    null,
                    static function (string $value) {
                        return new YearlyBonus((int) $value);
                    }
                );
                $bonusPolicy = new YearlyBonusSalaryPolicy($bonus);
                break;
            default:
                $style->error('Invalid policy');
                return 1;
        }

        $departmentId = new DepartmentId(new UUID($department->getId()->__toString()));
        $config = $this->departmentBonusSalaryPolicyRepository->findForDepartment($departmentId);
        if ($config) {
            $style->error(
                sprintf('Policy for department %s already exists', $departmentName)
            );
            return 1;
        }

        $this->departmentBonusSalaryPolicyRepository->save(
            new DepartmentBonusSalaryPolicyConfig(
                UUID::random(),
                $departmentId,
                $bonusPolicy
            )
        );

        return 0;
    }
}
