### PRACTICE: SWAGGER PETSTORE ↓

http://petstore.swagger.io/

### Initialize

```
mkdir swagger
cd swagger
composer require codeception/codeception --dev
```


### Install Codeception

```
./vendor/bin/codecept init api
```

URL: **http://petstore.swagger.io/v2**


### Create First Test

```
./vendor/bin/codecept g:cest api Pet
```


### Edit Test

`tests/PetsCest.php`

```php
    public function getAllPets(ApiTester $I)
    {
        $I->sendGET('/pet/findByStatus', [
          'status' => 'available'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
```


### Run:

```
./vendor/bin/codecept run --debug
```


### Create Test: Insert A Pet

```php
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
  }
```


### Check that Pet was actually created

```php
list($id) = $I->grabDataFromResponseByJsonPath('$.id');
codecept_debug($id);
$I->sendGET('/pet/' . $id);
$I->seeResponseContainsJson([
  'name' => 'dragon'
]);
```


### Update test to check JSON

* use `seeResponseIsJson()` to validate json
* use `seeResponseContainsJson` to check for data inclusion
* use `seeResponseMatchesJsonType` for data structure


```php
$I->seeResponseMatchesJsonType([
     'status_code' => 'integer',
     'message' => 'string|null',
     'data' => ['id' => 'integer']
]);
```


### Working Example

```php
public function insertPet(ApiTester $I)
{
    $I->haveHttpHeader('Accept', 'application/json');
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendPOST('/pet', [
        'name' => 'dragon123',
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
    $I->seeResponseContainsJson([
        'name' => 'dragon123',
        'status' => 'available',
    ]);
    $I->seeResponseMatchesJsonType([
        'name' => 'string',
        'status' => 'string',
        'id' => 'integer',
        'category' => 'array'
    ]);
    list($id) = $I->grabDataFromResponseByJsonPath('$..id');
    $I->sendGET('/pet/' . $id);
    $I->seeResponseCodeIs(200);
    $I->seeResponseContainsJson([
        'name' => 'dragon123'
    ]);
}
```


### Tasks

1. Update a PET
2. Delete a PET


## (Advanced) OPEN API Schena

Install Swagger Assertions

```
composer require fr3d/swagger-assertions
```


## Test

```php
use FR3D\SwaggerAssertions\PhpUnit\AssertsTrait;
use FR3D\SwaggerAssertions\SchemaManager;

class ApiCest 
{    

   use AssertsTrait;

    public function _before()
    {
        $this->schemaManager = SchemaManager::fromUri('http://petstore.swagger.io/v2/swagger.json');      
    }

    public function getPet(ApiTester $I)
    {
      $I->sendGET('/pet/1');
      $I->seeResponseCodeIs(200);
      $I->seeResponseIsJson();
      $response = json_decode($I->grabResponse());
      $this->assertResponseBodyMatch($this->schemaManager, 
        '/v2/pet/1', 
        'get', 
        200);
    }
```


### Move Schema Validation To Helper

* Separate support code from actual test:

```php
public function seeSchemaIsValid($method, $uri, $code = 200)
{
      $this->assertResponseBodyMatch($this->schemaManager, 
        $uri, 
        $method, 
        $code);

}
```
