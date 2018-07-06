<?php
namespace tests\api;

use ApiTester;

class CompanyCest
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
        $I->wantTo('Get all companies via API {/companies/}');
        $I->sendGET('/companies/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get company by id via API {/companies/1}');
        $I->sendGET('/companies/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}