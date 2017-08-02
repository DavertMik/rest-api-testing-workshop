## Practice

---

### Acceptance Tests with PhpBrowser

* Testing legacy app from the outside
* Emulating browser with HTTP requests
* Faster than real browser, no JavaScript

---

### Goals 

* Learn how to test system from outside
* How to write scenarios in Codeception
* Learn how to refactor tests

---

### WordPress

``` 
composer require johnpbloch/wordpress
cd wordpress
php -S 127.0.0.1:8000
```

Open browser and follow WP instructions

---

### Setup

* Create database `wordpress`
* User `admin`
* Password `admins`

---

### Install Codeception

```
composer require codeception/codeception --dev
./vendor/bin/codecept bootstrap
```

Edit `tests/acceptance.suite.yml`:

```yml
actor: AcceptanceTester
modules:
    enabled:
        - PhpBrowser:
            url: http://127.0.0.1:8000/
        - \Helper\Acceptance
```

---

### Reference Links

* [AcceptanceTests](http://codeception.com/docs/03-AcceptanceTests)
* [PhpBrowser](http://codeception.com/docs/modules/PhpBrowser)
* [Sequence](http://codeception.com/docs/modules/Sequence)
* [Testing WordPress plugins](http://codeception.com/07-24-2013/testing-wordpress-plugins.html)

---

### Write First Login Test

* Edit `tests/acceptance.yml`
* Create LoginCest:

```
./vendor/bin/codecept g:cest acceptance Login
```

---

### Login as Admin

```php
public function shouldLoginSuccessfully(AcceptanceTester $I)
{        
    $I->amOnPage('/wp-admin');
    $I->fillField('Username or Email Address', 'admin');
    $I->fillField('Password', 'admins');        
    $I->click('Log In');
    $I->see('Dashboard', 'h1');
}
```

---

### Add more tests

* Write a negative test
* Alternative login:

```php
    public function shouldLoginAsAdmin(AcceptanceTester $I)
    {
        $I->amOnPage('/wp-admin');
        $I->submitForm('#loginform', [
            'log' => 'admin',
            'pwd' => 'admins'
        ]);
        $I->see('Dashboard', 'h1');
    } 
```

---

### Run

```
./vendor/bin/codecept run
```

with steps

```
./vendor/bin/codecept run --steps
```

with debug info

```
./vendor/bin/codecept run --debug
```

if a test fails, see HTML in `tests/_outptut`

---

### Create New Post

```
./vendor/bin/codecept g:cest acceptance Post
```

---

Login before the test

```php
    public function createNewPost(AcceptanceTester $I)
    {
        $I->click('Write your first blog post');
        $I->see('Add New Post', 'h1');
        $I->fillField('Enter title here', 'My first post');
        $I->fillField('content', 'It is very interesting, please read it');
        $I->click('Publish');
        $I->see('Post published');
    }
```

---

### JavaScript is not enabled, 

"Post published" is not shown :(
Refactor to use `$I->submitForm`

---

```php
public function createNewPost(AcceptanceTester $I)
{
    $I->click('Write your first blog post');
    $I->see('Add New Post', 'h1');
    $I->submitForm('#post', [
        'post_title' => 'My first post',
        'content' => 'It is very interesting, please read it'
    ]);
    $I->see('Post published', '#message');
    $I->see('Status: Published');
}
```

---

### Submitted as Draft

Use `publish` button to actually publish post

```php
$I->submitForm('#post', [
    'post_title' => 'My second post',
    'content' => 'It is very interesting, please read it'
], 'publish');
```

---

Check the post is listed in `All Posts`

---

```php
$I->click('All Posts');
$I->see('My first post');
```


---


Each test creates its own post.
What if the last test fails? Post is still in the list!

---

## Data Management

* Recreate the database
* Create unique data per test

---

## Unique Data Set

* enable `Sequence` module
* refactor test:

```php
$I->submitForm('#post', [
    'post_title' => sq('post'),
    'content' => 'It is very interesting, please read it'
], 'publish');
$I->see('Post published', '#message');
$I->see('Status: Published');
$I->click('All Posts');
$I->see(sq('post'));
```

---

### See Post

```php
$I->submitForm('#post', [
    'post_title' => sq('post'),
    'content' => 'It is very interesting, please read it'
], 'publish');
$I->see('Post published', '#message');
$I->click('#sample-permalink');
$I->see(sq('post'), 'h1');
$I->see('It is very interesting, please read it');
```

---

## Next Tasks

* Edit Post
* Delete Post
* Get post ID from URL
* Refactor
  * move login to Actor
  * move create post to AdminSteps

---

## Atomic Tests

* Test should focus for one feature
* Split "create post" into
  * create post
  * create draft
  * see post on frontend
  * see post listed on page

---

## Complex Test: Comments

* Create 3 users
* Each user should write a comment per test
* Check that unauthorized user can't comment

---

## Principles

* Each test should be easy to understand
* Each should be easy to read and edit
* Tests should rely on stable parts
* Each test focused on a single feature

---

## BDD

* Let's describe business logic in feature file

```
./vendor/bin/codecept g:feature acceptance PostPublishing        
```

---

```gherkin
Feature: Post Publishing
  In order to update a website
  As a blog administrator
  I need to be able to publish a post

  Scenario: publish simple blogpost
    Given I am blog administrator
    When I enter admin dashboard
    And I create new post "Game Of Throne Spoilers"
"""
Everyone dies
Except John Snow
"""
    And I publish it
    Then I should see it on site
```

---

### Run

```
./vendor/bin/codecept run tests/acceptance/PostPublishing.feature --steps
```

---

### Steps Are Not Defined

Generate them:

```
./vendor/bin/codecept gherkin:snippets
```

* Copy them to `tests/_support/AcceptanceTester`
* Implement them!

---

### Free Ride
