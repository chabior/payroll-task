<?php

declare(strict_types=1);

namespace App\Department\Domain;

class Department
{
    public function __construct(
        private readonly DepartmentId $departmentId,
        private readonly DepartmentName $departmentName
    ) {
    }

    public function getName(): DepartmentName
    {
        return $this->departmentName;
    }

    public function getId(): DepartmentId
    {
        return $this->departmentId;
    }
}
