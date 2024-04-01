<?php

declare(strict_types=1);

namespace Taras\Lab3;

class CompanyService implements CompanyServiceInterface
{

    public function getTopLevelParent(Company $child): ?Company
    {
        $result = $child;

        $parentCompany = $child->getParent();

        while ($parentCompany) {
            $result = $parentCompany;

            $parentCompany = $result->getParent();
        }

        return $result;
    }

    /**
     * @param Company $targetCompany
     * @param array<Company> $companies
     * @return int
     */
    public function getEmployeeCountForCompanyAndChildren(Company $targetCompany, array $companies): int
    {
        $employeeCount = $targetCompany->getEmployeeCount();

        foreach ($companies as $company) {
            if ($company === $targetCompany) {
                continue;
            }

            if ($this->checkIsCompanyChild($company, $targetCompany)) {
                $employeeCount += $company->getEmployeeCount();
            }
        }

        return $employeeCount;
    }
    protected function checkIsCompanyChild(Company $company, Company $potentialParent): bool
    {
        $parent = $company->getParent();

        if ($parent === $potentialParent) {
            return true;
        }

        if ($parent === null) {
            return false;
        }

        return $this->checkIsCompanyChild($parent, $potentialParent);
    }
}
