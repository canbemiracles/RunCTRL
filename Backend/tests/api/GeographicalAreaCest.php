<?php
namespace tests\api;

use ApiTester;

class GeographicalAreaCest
{
    public function _before(ApiTester $I) {
    }

    public function _after(ApiTester $I) {
    }

    /**
     * @group other
     */
    public function getAllTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_OWNER');
        $I->wantTo('Get all geographical_areas via API {/geographical_areas/}');
        $I->sendGET('/geographical_areas/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_OWNER');
        $I->wantTo('Get geographical_area by id via API {/geographical_areas/1}');
        $I->sendGET('/geographical_areas/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}