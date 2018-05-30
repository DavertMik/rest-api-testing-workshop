## PRACTICE: Star Wars ↓

https://swapi.co

Create new API project.

```
mkdir starwars
cd starwars
composer require codeception/codeception --dev
```

or download [codecept.phar](https://codeception.com/codecept.phar)


### Install Codeception

```
./vendor/bin/codecept init api
```

URL: `https://swapi.co/api/`


### Edit Test

`tests/ApiCest.php`

```php
    public function getAllPets(ApiTester $I)
    {
        $I->sendGET('/people/1/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
```


### Run:

```
./vendor/bin/codecept run --debug
```


### Getting Data Without JSONPath

```php
$I->sendGET('/people/1');
$data = json_decode($I->grabResponse(), true);
$I->assertEquals('Luke Skywalker', $data['name']);
```


### Tasks

1. List and assert all films with Luke Skywalker
  * use `grabDataFromResponseByJsonPath` to get film id
  * What is [JsonPath](http://goessner.net/articles/JsonPath/)? XPath for JSON!
  * Use `Asserts` module
  * [JSONPath tester](https://jsonpath.curiousconcept.com)
2. Verify that R2D2 was in the same film with Death Star


### Install JsonPath

```
composer require flow/jsonpath --dev  `
```


### Enable Asserts

```yml
suite:
      api:
          actor: ApiTester
          modules:
              enabled:
                  - REST:
                       depends: PhpBrowser
                  - Asserts
```


```php
// Luke Skywalker films
$films = [
  "The Empire Strikes Back",
  "Revenge of the Sith",
  "A New Hope",
  "Return of the Jedi",
  "The Force Awakens"
];
$I->sendGET('/people/1');
// $I->sendGET('/people', ['name' => "Luke Skywalker"]);
// GET ALL FILMS
// For each film send reqest
// Check that the film is in a list
```


```php
public function tryApi(ApiTester $I)
{
    // Luke Skywalker films
    $films = [
      "The Empire Strikes Back",
      "Revenge of the Sith",
      "A New Hope",
      "Return of the Jedi",
      "The Force Awakens"
    ];

    $I->sendGET('/people/1');
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
    $I->seeResponseContainsJson([
       'name' => 'Luke Skywalker'
    ]);
    // USING JSON API 
    $films = $I->grabDataFromResponseByJsonPath('$..films');
```


### Working Example!

```php
public function tryApi(ApiTester $I)
{
    // Luke Skywalker films
    $films = [
      "The Empire Strikes Back",
      "Revenge of the Sith",
      "A New Hope",
      "Return of the Jedi",
      "The Force Awakens"
    ];

    $I->sendGET('/people/1');
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
    $I->seeResponseContainsJson([
       'name' => 'Luke Skywalker'
    ]);
    $films = $I->grabDataFromResponseByJsonPath('$..films');

    $actualFilmTitles = [];

    list($urls) = $films;
    $I->assertCount(5, $urls);
    codecept_debug($films);
    foreach ($urls as $url) {
        $I->sendGET($url);
        $I->seeResponseCodeIs(200);
        list($title) = $I->grabDataFromResponseByJsonPath('$.title');
        $actualFilmTitles[] = $title;
    }
    codecept_debug($actualFilmTitles);

    $I->assertEquals(sort($films), sort($actualFilmTitles));
}
```



## (Advanced) [JsonSchema](https://jsonschema.net/)

* https://swapi.co/documentation#schema
* used for structure definition and validation
* check JSONSchema for responses

```
composer require justinrainbow/json-schema
```


* Create `Helper\Api` (unless it exists)
* Append `\Helper\Api` to list of enabled modules:


```
suite:
      api:
          actor: ApiTester
          modules:
              enabled:
                  - REST:
                       depends: PhpBrowser
                  - \Helper\Api
```


### Add to Helper\Api

```php
public function seeResponseMatchesJsonSchema($pathToSchema)
{
    $data = json_decode($this->getModule('REST')->response);

    // Validate
    $validator = new \JsonSchema\Validator;
    $validator->validate($data, (object)['$ref' => $pathToSchema]);

    if ($validator->isValid()) {
        return $this->assertTrue(true);
    }
    $errors = '';
    foreach ($validator->getErrors() as $error) {
        $errors .= "\n {$error['property']} {$error['message']}";
    }
    $this->fail("Response doesn't match JSON schema at $pathToSchema" . $errors);
}
```


```php
$I->sendGET('/people/1');
$I->seeResponseMatchesJsonSchema('http://swapi.co/api/people/schema');
```


