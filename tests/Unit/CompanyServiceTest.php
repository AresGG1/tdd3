<?php

declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\TestCase;
use Taras\Lab3\Company;
use Taras\Lab3\CompanyService;
use Taras\Lab3\CompanyServiceInterface;

class CompanyServiceTest  extends TestCase
{
    private CompanyServiceInterface $companyService;
    private \ReflectionMethod $checkIfChild;
    public function setUp(): void
    {
        $this->companyService = new CompanyService();

        // Use reflection to test private method
        $reflectionService = new \ReflectionClass($this->companyService);
        $method = $reflectionService->getMethod('checkIsCompanyChild');

        $method->setAccessible(true);

        $this->checkIfChild = $method;
    }

    /*
     * Implement top level parent functionality
     *
     */

    public function test_company_itself_is_top_level()
    {
        $company = new Company(3);

        $topLevelParent = $this->companyService->getTopLevelParent($company);

        $this->assertEquals($company, $topLevelParent);
    }

    public function test_return_single_parent()
    {
        $parent = new Company(2);
        $child = new Company(3, $parent);

        $topLevelParent = $this->companyService->getTopLevelParent($child);

        $this->assertSame($parent, $topLevelParent);
    }
    public function test_deepest_parent_is_top_level()
    {
        $secondLevelParent = new Company(2);
        $firstLevelParent = new Company(4, $secondLevelParent);
        $child = new Company(3, $firstLevelParent);

        $topLevelParent = $this->companyService->getTopLevelParent($child);

        $this->assertSame($secondLevelParent, $topLevelParent);
    }

    /*
    * Implement check if child functionality
    *
    */

    public function test_if_first_level_child_returns_true()
    {
        $parent = new Company(2);
        $child = new Company(3, $parent);

        //Calling reflection method
        $result = $this->checkIfChild->invoke(
            $this->companyService,
            $child,
            $parent
        );

        $this->assertSame(true, $result);
    }

    public function test_if_false_on_wrong_company()
    {
        $parent = new Company(2);
        $child = new Company(3, $parent);
        $unrelatedCompany = new Company(2);


        $result = $this->checkIfChild->invoke(
            $this->companyService,
            $child,
            $unrelatedCompany
        );

        $this->assertSame(false, $result);
    }
    public function test_second_level_child_returns_true()
    {
        $parent = new Company(2);
        $child = new Company(3, $parent);
        $secondLevelChild = new Company(2, $child);


        $result = $this->checkIfChild->invoke(
            $this->companyService,
            $secondLevelChild,
            $parent
        );

        $this->assertSame(true, $result);
    }

    /*
    * Implement getEmployeeCountForCompanyAndChildren
    *
    */
    public function test_one_company_returns_employees_count()
    {
        $employeesNumber = 3;
        $company = new Company($employeesNumber);
        $allCompaniesArray = [$company];

        $employeesNumberResult = $this->companyService
            ->getEmployeeCountForCompanyAndChildren(
                $company,
                $allCompaniesArray
        );

        $this->assertSame($employeesNumber, $employeesNumberResult);
    }
    public function test_company_and_its_child()
    {
        //6
        $company = new Company(3);
        $child = new Company(3, $company);
        $allCompaniesArray = [$company, $child];

        $employeesNumberResult = $this->companyService
            ->getEmployeeCountForCompanyAndChildren(
                $company,
                $allCompaniesArray
            );

        $this->assertSame(6, $employeesNumberResult);
    }

    public function test_with_unrelated_companies()
    {
        $company = new Company(3);
        $unrelatedCompany = new Company(2);
        $unrelatedChild = new Company(2, $unrelatedCompany);
        $allCompaniesArray = [$company, $unrelatedCompany, $unrelatedChild];

        $employeesNumberResult = $this->companyService
            ->getEmployeeCountForCompanyAndChildren(
                $company,
                $allCompaniesArray
            );

        $this->assertSame(3, $employeesNumberResult);
    }

    public function test_with_second_level_children_and_unrelated_companies()
    {
        //10
        $company = new Company(3);
        $child = new Company(2, $company);
        $anotherChild = new Company(3, $company);
        $secondLevelChild = new Company(2, $child);

        $unrelatedCompany = new Company(2);
        $unrelatedChild = new Company(2, $unrelatedCompany);

        $allCompaniesArray = [$company,
            $unrelatedCompany,
            $unrelatedChild,
            $child,
            $anotherChild,
            $secondLevelChild
        ];

        $employeesNumberResult = $this->companyService
            ->getEmployeeCountForCompanyAndChildren(
                $company,
                $allCompaniesArray
            );

        $this->assertSame(10, $employeesNumberResult);
    }
}
