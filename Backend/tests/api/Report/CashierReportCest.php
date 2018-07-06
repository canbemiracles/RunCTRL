<?php
namespace tests\api\Report;

use ApiTester;

class CashierReportCest
{
    public function _before(ApiTester $I) {
    }

    public function _after(ApiTester $I) {
    }

    /**
     * @group report
     */
    public function getAllTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get all reports via API {/branches/1/stations/1/reports/cashier_reports}');
        $I->sendGET('/branches/1/stations/1/reports/cashier_reports');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group report
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get report by id via API {/branches/1/stations/1/reports/cashier_reports/1}');
        $I->sendGET('/branches/1/stations/1/reports/cashier_reports/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}