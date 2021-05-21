<?php

class SeeContentCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function ISeeRootOverview(\ApiTester $I)
    {
        $id = $I->havePostInDatabase([
            'post_type'   => 'pdc-location',
            'post_status' => 'publish',
        ]);

        $I->sendGet('/locations');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('data.[0].title');
        $I->seeResponseContainsJson([
            'data' => [],
        ]);
    }

    public function ISeeASingleItem(\ApiTester $I)
    {
        $id = $I->havePostInDatabase([
            'post_type'   => 'pdc-location',
            'post_status' => 'publish',
        ]);

        $I->sendGet('/locations/'. $id);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'title'   => 'string',
            'date'    => 'string',
            'general' => [
                'description' => 'string',
            ],
        ]);
    }
}
