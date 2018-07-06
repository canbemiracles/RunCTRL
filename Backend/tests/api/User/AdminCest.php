<?php
namespace tests\api\User;

use ApiTester;

class AdminCest
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
        $I->wantTo('Get all admins via API {/companies/1/users/admins/}');
        $I->sendGET('/companies/1/users/admins/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group user
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get admin by id via API {/companies/1/users/admins/1}');
        $I->sendGET('/companies/1/users/admins/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}