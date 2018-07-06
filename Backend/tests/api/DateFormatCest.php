<?php
namespace tests\api;

use ApiTester;

class DateFormatCest
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
        $I->wantTo('Get all date_formats via API {/date_formats/}');
        $I->sendGET('/date_formats/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get date_format by id via API {/date_formats/1}');
        $I->sendGET('/date_formats/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}