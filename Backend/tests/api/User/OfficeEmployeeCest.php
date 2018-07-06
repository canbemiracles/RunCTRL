<?php
namespace tests\api\User;

use ApiTester;

class OfficeEmployeeCest
{
    public function _before(ApiTester $I) {
    }

    public function _after(ApiTester $I) {
    }

    /**
     * @group user
     */
    public function getAllTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_OFFICE_EMPLOYEE');
        $I->wantTo('Get all office_employees via API {/companies/1/users/office_employees/}');
        $I->sendGET('/companies/1/users/office_employees/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group user
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_OFFICE_EMPLOYEE');
        $I->wantTo('Get office_employee by id via API {/companies/1/users/office_employees/5}');
        $I->sendGET('/companies/1/users/office_employees/5');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}