<?php
namespace tests\api\User;

use ApiTester;

class BranchManagerCest
{
    public function _before(ApiTester $I) {
    }

    public function _after(ApiTester $I) {
    }

    /**
     * @group user
     */
    public function getAllTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get all branch_managers via API {/companies/1/users/branch_managers/}');
        $I->sendGET('/companies/1/users/branch_managers/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group user
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_BRANCH_MANAGER');
        $I->wantTo('Get branch_manager by id via API {/companies/1/users/branch_managers/3}');
        $I->sendGET('/companies/1/users/branch_managers/3');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}