<?php
namespace tests\api;

use ApiTester;

class TimeZoneCest
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
        $I->wantTo('Get all time_zones via API {/time_zones/}');
        $I->sendGET('/time_zones/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get time_zone by id via API {/time_zones/1}');
        $I->sendGET('/time_zones/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}