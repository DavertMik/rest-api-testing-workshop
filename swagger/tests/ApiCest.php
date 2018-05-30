<?php
use FR3D\SwaggerAssertions\PhpUnit\AssertsTrait;
use FR3D\SwaggerAssertions\SchemaManager;

class ApiCest 
{    

   use AssertsTrait;

    public function _before()
    {
        $this->schemaManager = SchemaManager::fromUri('http://petstore.swagger.io/v2/swagger.json');      
    }

    public function tryApi(ApiTester $I)
    {
        $I->sendGET('/pet/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        codecept_debug($I->grabResponse());
        $this->assertResponseBodyMatch(json_decode($I->grabResponse()), $this->schemaManager, '/v2/pet/1', 'get', 200);
    }

    public function createPet(ApiTester $I)
    {
        $I->haveHttpHeader('api_key', 'special-key');
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/pet', [
          'name' => 'dragon',
          'category' => [
            'id' => 0,
            "name" => "string",
          ],
          'status' => 'available',
          'photoUrls' => [
            'https://pixabay.com/en/dragon-illusions-silhouette-fantasy-2794030/'
          ]
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        list($id) = $I->grabDataFromResponseByJsonPath('$.id');
        codecept_debug($id);
        $I->sendGET('/pet/' . $id);
        $I->seeResponseContainsJson([
          'name' => 'dragon'
        ]);
    }
}