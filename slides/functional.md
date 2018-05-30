## Functional Tests

* Term is ambiguous
* Can refer to acceptance testing or integration testing

---

### In Codeception

> Functional tests are inner tests acting like acceptance tests.

Require one of the supported frameworks

---

```php
$I->amOnPage('/register');
$I->fillField('fullName', 'Michael B');
$I->fillField('email', 'davert@codeception.com');
$I->fillField('password', '123456789');
$I->click('Register');
$I->see('Hud', 'h1');
$I->seeRecord(\App\User::class, [
    'full_name' => 'Michael B', 
    'email' => ' davert@codeception.com'
]);
```

---

## Benefits over Acceptance Tests

* **Fast** as integration test
* WebServer is not required
* Access application services
  * ORM - test database updates
  * Mailer - test emails are sent
  * Queue - test jobs are sent to queue

---

## Benefits over integration tests

* Scenario-Driven testing
* Replaces controller tests

---

## How They Work

1. Emulating request to application by setting $_GET. $_POST
1. Starting application (as in index.php)
1. Storing output to variable, instead of printing
1. Parsing output
1. Sending next request

(a framework is needed to perform this smoothly)

---

> Functional tests have the right balance between speed and flexibility

