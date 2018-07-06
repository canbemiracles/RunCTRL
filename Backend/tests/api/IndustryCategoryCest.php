<?php
namespace tests\api;

use ApiTester;

class IndustryCategoryCest
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
        $I->wantTo('Get all industry_categories via API {/industry_categories/}');
        $I->sendGET('/industry_categories/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get industry_category by id via API {/industry_categories/1}');
        $I->sendGET('/industry_categories/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}