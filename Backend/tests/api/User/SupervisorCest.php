<?php
namespace tests\api\User;

use ApiTester;

class SupervisorCest
{
    public function _before(ApiTester $I) {
    }

    public function _after(ApiTester $I) {
    }

    /**
     * @group user
     */
    public function getAllTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_SUPERVISOR');
        $I->wantTo('Get all supervisors via API {/companies/1/users/supervisors/}');
        $I->sendGET('/companies/1/users/supervisors/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group user
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_SUPERVISOR');
        $I->wantTo('Get supervisor by id via API {/companies/1/users/supervisors/2}');
        $I->sendGET('/companies/1/users/supervisors/2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}