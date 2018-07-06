<?php
namespace tests\api;

use ApiTester;

class EmployeeCest
{
    public function _before(ApiTester $I) {
    }

    public function _after(ApiTester $I) {
    }

    /**
     * @group other
     */
    public function getAllTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get all employees via API {/branches/1/employees/}');
        $I->sendGET('/companies/1/employees/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get employee by id via API {/branches/1/employees/1}');
        $I->sendGET('/companies/1/employees/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}