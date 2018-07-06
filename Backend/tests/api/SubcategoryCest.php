<?php
namespace tests\api;

use ApiTester;

class SubcategoryCest
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
        $I->wantTo('Get all subcategories via API {/industry_categories/1/subcategories/}');
        $I->sendGET('/industry_categories/1/subcategories/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get subcategory by id via API {/industry_categories/1/subcategories/1}');
        $I->sendGET('/industry_categories/1/subcategories/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}