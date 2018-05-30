
### Practice: Laravel App ↓

Project: Ribbon https://github.com/DavertMik/ribbbon

#### Install Project & Run Server

```
git clone https://github.com/DavertMik/ribbbon.git
cd ribbon
composer install
cp .env.example .env
```


### Database Setup

Check that SQLite is enabled:

```
php -m | grep sqlite
```


### Check .env file:

```
DB_CONNECTION=sqlite
DB_HOST=localhost

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

[MySQL Setup](https://gist.github.com/DavertMik/4394c13c7a556994d4fcce3c0fa38f77)



Start Server:

```
php artisan serve
```

EMAIL: `test@ribbbon.com` PASSWORD: `secret`


Install Codeception

```
composer require codeception/codeception --dev
```
or download [codecept.phar](https://codeception.com/codecept.phar)


#### Initialize

```
./vendor/bin/codecept bootstrap --empty
```

#### Create API Suite

```
./vendor/bin/codecept g:suite api
```


## Testing REST API for Laravel ↓


```
php artisan route:list
```

|          |                                      |
|----------|--------------------------------------|
| POST     | api/clients                          |
| PUT      | api/clients/{id}                     |
| DELETE   | api/clients/{id}                     |
| GET|HEAD | api/clients/{withWeight?}            |
| POST     | api/credentials                      |
| DELETE   | api/credentials/{id}                 |
| PUT      | api/credentials/{id}                 |
| GET|HEAD | api/credentials/{id}                 |
| GET|HEAD | api/projects                         |
| POST     | api/projects                         |
| GET|HEAD | api/projects/shared                  |
| PUT      | api/projects/{id}                    |
| GET|HEAD | api/projects/{id}                    |
| GET|HEAD | api/projects/{id}/members            |
| GET|HEAD | api/projects/{id}/owner              |
| POST     | api/projects/{id}/{email}/invite     |
| DELETE   | api/projects/{id}/{member_id}/remove |
| GET|HEAD | api/tasks                            |
| POST     | api/tasks/{client_id}/{project_id}   |
| DELETE   | api/tasks/{id}                       |
| PUT      | api/tasks/{id}                       |
| GET|HEAD | api/user                             |
| DELETE   | api/user                             |
| POST     | api/user/{id}                        |


## Configure

in `tests/api.suite.yml`

```yml
class_name: ApiTester
modules:
    enabled:
        - REST:
            depends: Laravel5
        - Laravel5            
        - \Helper\Api
```


## Create New Test

```
./vendor/bin/codecept g:cest api Client
```



```php
class APICest
{
    public function _before(ApiTester $I)
    {
        // authorize
        $I->sendPOST('/login', [
          'email' => 'test@ribbbon.com',
          'password' => 'secret'
        ]);
    }

    // tests
    public function createClient(ApiTester $I)
    {
        $I->sendPOST('/api/clients', ['user_id' => '1', 'name' => 'davert']);
        $I->seeResponseCodeIs(200);
    }
}
```


## Run

```
./vendor/bin/codecept run --debug
```


## Tasks

* can't create client without data
  * see doesn' create it `dontSeeRecord`
* update client
  * create it with `haveRecord`
  * see changes with `seeRecord`
* delete client
  * create it with `haveRecord`
  * see it's gone with `dontSeeRecord`


### Next

* write test for Project
* write test for Task


## Code Coverage

Add to codeception.yml:

```yml
coverage:
    enabled: true
    include:
        - app/*
```

Run tests:

```
./vendor/bin/codecept run --coverage --coverage-html
```

CodeCoverage report is generated in `tests/_output/coverage`