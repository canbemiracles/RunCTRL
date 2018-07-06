<?php
namespace tests\api\Card;

use ApiTester;

class HistoryCardPaymentCest
{
    public function _before(ApiTester $I) {
    }

    public function _after(ApiTester $I) {
    }

    /**
     * @group other
     */
    public function getByIdTest(ApiTester $I) {
        $I->amBearerAuthenticated('TOKEN_ADMIN');
        $I->wantTo('Get history_card_payment by id via API {/history_card_payments/1}');
        $I->sendGET('/history_card_payments/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}