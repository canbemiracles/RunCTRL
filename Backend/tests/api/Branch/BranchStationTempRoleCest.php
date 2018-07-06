<?php
namespace tests\api\Branch;

use ApiTester;

class BranchStationTempRoleCest
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
        $I->wantTo('Get all branch_temp_roles via API {/branches/1/stations/1/temp_roles}');
        $I->sendGET('/branches/1/stations/1/temp_roles');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group branch
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get branch_temp_role by id via API {/branches/1/stations/1/temp_roles/3}');
        $I->sendGET('/branches/1/stations/1/temp_roles/3');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}