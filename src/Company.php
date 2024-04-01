<?php

declare(strict_types=1);

namespace Taras\Lab3;

class Company
{
    public function __construct(int $employeeCount, ?Company $parent=null)
    {
        $this->employeeCount = $employeeCount;
        $this->parent = $parent;
    }

    private ?Company $parent;
    private int $employeeCount;
    public function getParent(): ?Company
    {
        return $this->parent;
    }

    public function getEmployeeCount(): int
    {
        return $this->employeeCount;
    }
}
