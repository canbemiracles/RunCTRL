<?php
namespace tests\api\Recommendations;

use ApiTester;

class RecommendationsStationsCest
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
        $I->wantTo('Get all Recommendations via API {/industry_categories/1/subcategories/1/recommendations_stations/}');
        $I->sendGET('/industry_categories/1/subcategories/1/recommendations_stations/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get Recommendation by id via API {/industry_categories/1/subcategories/1/recommendations_stations/1}');
        $I->sendGET('/industry_categories/1/subcategories/1/recommendations_stations/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}