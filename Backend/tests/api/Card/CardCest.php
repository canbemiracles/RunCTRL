<?php
namespace tests\api\Card;

use ApiTester;

class CardCest
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
        $I->wantTo('Get card by id via API {/cards/1}');
        $I->sendGET('/cards/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}