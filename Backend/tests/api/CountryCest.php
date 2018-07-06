<?php
namespace tests\api;

use ApiTester;

class CountryCest
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
        $I->wantTo('Get all countries via API {/countries/}');
        $I->sendGET('/countries/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get country by id via API {/countries/1}');
        $I->sendGET('/countries/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}