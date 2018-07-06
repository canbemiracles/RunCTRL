<?php
namespace tests\api\Branch;

use ApiTester;

class BranchShiftCest
{
    public function _before(ApiTester $I) {
    }

    public function _after(ApiTester $I) {
    }

    /**
     * @group branch
     */
    public function getAllTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get all branch_shifts via API {/branches/1/shifts}');
        $I->sendGET('/branches/1/shifts');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group branch
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get branch_shift by id via API {/branches/1/shifts/1}');
        $I->sendGET('/branches/1/shifts/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}