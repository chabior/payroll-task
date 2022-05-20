<?php

declare(strict_types=1);

namespace App\Department\Infrastructure\Doctrine;

use App\Department\Domain\Department;
use App\Department\Domain\DepartmentName;
use App\Department\Domain\DepartmentRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineDepartmentRepository extends ServiceEntityRepository implements DepartmentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function save(Department $department)
    {
        $em = $this->getEntityManager();
        $em->persist($department);
        $em->flush();
    }

    public function all(): array
    {
        return $this->findAll();
    }

    public function findOneByName(DepartmentName $departmentName): ?Department
    {
        return $this->findOneBy(
            [
                'departmentName.name' => $departmentName->name,
            ]
        );
    }
}
