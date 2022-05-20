<?php

declare(strict_types=1);

namespace App\Payroll\Infrastructure\Doctrine;

use App\Payroll\Domain\DepartmentBonusSalaryPolicyConfig;
use App\Payroll\Domain\DepartmentBonusSalaryPolicyRepository;
use App\Payroll\Domain\DepartmentId;
use App\Payroll\Domain\Policy\Exception\NotUniqueBonusSalaryConfigException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ?DepartmentBonusSalaryPolicyConfig findOneBy(array $criteria, ?array $oderBy = null)
 */
class DoctrineDepartmentBonusSalarySalaryPolicyRepository extends ServiceEntityRepository implements
    DepartmentBonusSalaryPolicyRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepartmentBonusSalaryPolicyConfig::class);
    }

    public function findForDepartment(DepartmentId $departmentId): ?DepartmentBonusSalaryPolicyConfig
    {
        return $this->findOneBy(
            [
                'departmentId.department' => $departmentId->__toString(),
            ]
        );
    }

    public function save(DepartmentBonusSalaryPolicyConfig $departmentBonusSalaryPolicyConfig): void
    {
        try {
            $em = $this->getEntityManager();
            $em->persist($departmentBonusSalaryPolicyConfig);
            $em->flush();
        } catch (UniqueConstraintViolationException) {
            throw new NotUniqueBonusSalaryConfigException('Config for department already exists');
        }
    }
}
