<?php
namespace tests\api\Branch;

use ApiTester;

class BranchStationOriginRoleCest
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
        $I->wantTo('Get all branch_origin_roles via API {/branches/1/stations/1/origin_roles}');
        $I->sendGET('/branches/1/stations/1/origin_roles');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group branch
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get branch_origin_role by id via API {/branches/1/stations/1/origin_roles/1}');
        $I->sendGET('/branches/1/stations/1/origin_roles/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}