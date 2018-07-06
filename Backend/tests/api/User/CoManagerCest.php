<?php
namespace tests\api\User;

use ApiTester;

class CoManagerCest
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
        $I->wantTo('Get all co_managers via API {/companies/1/users/comanagers/}');
        $I->sendGET('/companies/1/users/comanagers/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group user
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_CO_MANAGER');
        $I->wantTo('Get co_manager by id via API {/companies/1/users/comanagers/4}');
        $I->sendGET('/companies/1/users/comanagers/4');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}