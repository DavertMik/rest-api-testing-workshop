<!-- data-separator-vertical="\n\n\n" data-background="#A0C66B" -->

## Practice

1. Setup Laravel application
1. Write functional tests
1. Learn how to check values in database

---

### Setup Project

Project: Ribbon https://github.com/DavertMik/ribbbon

Laravel-based Tasks Application

#### Install Project & Run Server

```
git clone git@github.com:davertmik/ribbbon.git
cd ribbon
composer install
cp .env.example .env
```



edit .env file:

```
DB_HOST=localhost
DB_DATABASE=ribbon
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=log
MAIL_HOST=localhost
MAIL_PORT=25
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

run commands

```
echo "create database ribbon" | mysql -uroot
php artisan migrate
php artisan db:seed
php artisan serve
```

#### Prepare Codeception

```
composer require --dev codeception/codeception
php vendor/bin/codecept bootstrap
```

* acceptance, **functional**, unit test suites created


Configure Codeception (tests/functional.suite.yml):

```yml
actor: FunctionalTester
modules:
    enabled:
        - Laravel5  
        - \Helper\Functional
```



## Open Ribbon Application 

```
https://localhost:8000
```

* clients
* projects
* tasks


## First Test: "Register a User"


```php
<?php
class RegisterCest
{
    public function registerUserSuccessfully(FunctionalTester $I)
    {
        $I->amOnPage('/register');
        $I->fillField('fullName', 'Michael B');
        $I->fillField('email', 'davert@codeception.com');
        $I->fillField('password', '123456789');
        $I->click('Register');
        $I->see('Hud', 'h1');
    }
}
```

### Execute

```
./vendor/bin/codecept run functional
```

Note: tests are executed inside PHP process. No webserver

---

## Data Management

* Using Framework's ORM
* Using Db module
* Custom solution


### Practice


### Goals

* Write functional tests
* Learn framework modules
* Cleanup database between tests
* Create data for a test


## Next: Write Tests For Unsuccessful Registration

* register without password
* register with invalid email
* register when user already exist


## Hints

* Check data has been already saved. 
  * Use `seeRecord`
* Add a test to check that user with occupied email can't be registered
  * Use `haveRecord`

---

### Data  Cleanup

* Check that database is not updated
* Create special database for tests and update the config to use it

```
cp .env .env.test
```

```yml
actor: FunctionalTester
modules:
    enabled:
        - Laravel5:
          environment_file: .env.test
        - \Helper\Functional
```

---

### CodeCoverage

* to detect untested parts of application
* provide stats of current test coverage

---

### Enable CodeCoverage

In `codeception.yml`

```yml
coverage:
    enabled: true
    include:
        - app/*
```

Execute tests:

```
./vendor/bin/codecept run functional --coverage --coverage-html
```