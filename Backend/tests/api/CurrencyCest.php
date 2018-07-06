<?php
namespace tests\api;

use ApiTester;

class CurrencyCest
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
        $I->wantTo('Get all currencies via API {/currencies/}');
        $I->sendGET('/currencies/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get currency by id via API {/currencies/1}');
        $I->sendGET('/currencies/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}