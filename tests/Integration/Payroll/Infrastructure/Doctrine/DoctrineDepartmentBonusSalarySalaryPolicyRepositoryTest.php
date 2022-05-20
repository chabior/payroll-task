<?php

declare(strict_types=1);

namespace App\Tests\Integration\Payroll\Infrastructure\Doctrine;

use App\Common\UUID;
use App\Payroll\Domain\DepartmentBonusSalaryPolicyConfig;
use App\Payroll\Domain\DepartmentId;
use App\Payroll\Domain\Policy\Exception\NotUniqueBonusSalaryConfigException;
use App\Payroll\Domain\Policy\NoBonusPolicy;
use App\Payroll\Infrastructure\Doctrine\DoctrineDepartmentBonusSalarySalaryPolicyRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineDepartmentBonusSalarySalaryPolicyRepositoryTest extends KernelTestCase
{
    private DoctrineDepartmentBonusSalarySalaryPolicyRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->repo = self::getContainer()->get(DoctrineDepartmentBonusSalarySalaryPolicyRepository::class)
        ;
    }

    public function testConfigCanBeSaved(): void
    {
        $id = UUID::random();
        $this->repo->save(
            new DepartmentBonusSalaryPolicyConfig(
                $id,
                new DepartmentId(UUID::random()),
                new NoBonusPolicy()
            )
        );

        $config = $this->repo->find($id);
        $this::assertNotNull($config);
    }

    public function testOnlyOneConfigForDepartmentCanBeSaved(): void
    {
        $this->expectException(NotUniqueBonusSalaryConfigException::class);

        $id = UUID::random();
        $this->repo->save(
            new DepartmentBonusSalaryPolicyConfig(
                $id,
                new DepartmentId(UUID::random()),
                new NoBonusPolicy()
            )
        );

        $this->repo->save(
            new DepartmentBonusSalaryPolicyConfig(
                $id,
                new DepartmentId(UUID::random()),
                new NoBonusPolicy()
            )
        );

    }
}
