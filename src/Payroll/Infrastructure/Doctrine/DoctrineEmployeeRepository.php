<?php

declare(strict_types=1);

namespace App\Payroll\Infrastructure\Doctrine;

use App\Payroll\Domain\Employee;
use App\Payroll\Domain\EmployeeRepository;
use App\Payroll\Domain\Policy\Exception\NotUniqueEmployeeException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method list<Employee> findAll()
 */
class DoctrineEmployeeRepository extends ServiceEntityRepository implements EmployeeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employee): void
    {
        try {
            $em = $this->getEntityManager();
            $em->persist($employee);
            $em->flush();
        } catch (UniqueConstraintViolationException) {
            throw new NotUniqueEmployeeException(
                'Employee already exists'
            );
        }
    }

    public function all(): array
    {
        return $this->findAll();
    }
}
