<?php

namespace Taras\Lab3;

interface CompanyServiceInterface
{
    public function getTopLevelParent(Company $child): ?Company;

    public function getEmployeeCountForCompanyAndChildren(Company $targetCompany, array $companies): int;
}