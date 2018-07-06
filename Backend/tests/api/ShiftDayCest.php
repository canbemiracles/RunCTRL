<?php
namespace tests\api;

use ApiTester;

class ShiftDayCest
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
        $I->wantTo('Get all shift_days via API {/shift_days/}');
        $I->sendGET('/shift_days/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get shift_day by id via API {/shift_days/1}');
        $I->sendGET('/shift_days/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}