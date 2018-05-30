<!-- data-separator-vertical="\n\n\n" data-background="#A0C66B" -->

## Plan for Today

1. Setup Laravel+Vue application
1. Install Selenium Server
1. Write acceptance tests
1. Learn about locators (CSS, XPath)
1. Dealing with JavaScript (selectize, angular, vue.js)
1. Using Recorder Extension
1. Page Object pattern
1. Learn about managing data

---

### Setup Project

Project: Ribbon https://github.com/DavertMik/ribbbon

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

* acceptance, ~~functional, unit~~ test suites created


Configure Codeception (tests/acceptance.suite.yml):

```yml
class_name: AcceptanceTester
modules:
    enabled:
        - WebDriver:
            url: http://127.0.0.1:8000
            browser: chrome
        - \Helper\Acceptance
```



## Open Ribbon Application 

```
https://localhost:8000
```

* clients
* projects
* tasks


### Start Selenium

**Download**: http://www.seleniumhq.org/download/

```
wget https://goo.gl/Lyo36k
java -jar selenium-server-standalone-3.0.1.jar 
```

***

or headlessly, using Docker:

```
docker run --net=host selenium/standalone-chrome:3.0.1 
```

(optionally)

---

## First Test: "Create Task"


Try Codeception in action

```
./vendor/bin/codecept console acceptance
```


* Prepare initial data:
 * Register
 * Create Client
 * Create Project


```
./vendor/bin/codecept g:cest acceptance Tasks
```


Make a first test

```php
<?php
class TaskCest
{
    public function createTask(AcceptanceTester $I)
    {
        $I->amOnPage('/projects/2');
        $I->seeElement('.new-task');
    }
}
```


* Find which elements are needed to create a task
* Find how to locate those elements.
* What is expected result?


### Locators

|      |     |
|---|---|
| CSS | `$I->seeElement('.new-task')` |
| XPath | `$I->seeElement('//table/tr/td[2]')` |
| Fuzzy | `$I->click('Hello')` |
| Strict | `$I->click(['xpath' => '//nav/a[2]']);` |



```php
public function createTask(AcceptanceTester $I)
{
    $I->amOnPage('/projects/2');
    $I->click('New Task');
    $I->seeElement('.new-task');
    $I->fillField('.new-task .first', 'Please fix that');
    $I->fillField('.new-task textarea', 'This is very important task');
    $I->click('Save', '.new-task');
    $I->waitForElementNotVisible('.new-task');
    $I->see('Please fix that', '.task-list');
}
```


* **HINT** Use the simplest locator as possible
* **HINT** Rely on the most stable locators
* **HINT** Add special classes to anonymous elements


* Edit `resources/views/ins/project/partials/forms`
* Add lc- style locators.
* Rewrite test:

```php
public function createTask(AcceptanceTester $I)
{
    $I->amOnPage('/projects/2');
    $I->click('New Task');
    $I->seeElement('.new-task');
    $I->fillField('.new-task .lc-title', 'Please fix that');
    $I->fillField('.new-task .lc-description', 'This is very important task');
    $I->click('Save', '.new-task');
    $I->waitForElementNotVisible('.new-task');
    $I->see('Please fix that', '.task-list');
}
```

---

## Write More Tests

* Move login logic to `_before()`
* Add `changePriority` test
* Add `deleteTask` Test

---

## JS Issues

Enhance Application with Selectize.js

```
git branch selectize
git checkout selectize
git pull origin selectize
git checkout master
git merge selectize
```


Run change priority test

```
./vendor/bin/codecept run tests/acceptance/TasksCest:changePriority
```


### Failed :( 

**selectOption** doesn't work with non-native selects.

* Make a custom method to set value in `selectize` selects. 
* Use [Selectize API](https://github.com/selectize/selectize.js/blob/master/docs/api.md) to change value. 
* Use `executeJS` to execute JS code
* Update `AcceptanceTester` to include `selectizeOption` method


```php
public function selectizeOption($css, $value)
{
    $this->executeJS("$('$css')[0].selectize.setValue('$value')");
}
```

---


## JS Issues Part 2: Single Page Apps

* EventLoop: Rendering in SPA 
* Waiting for Loop to finish. [Example in AngularJS](github.com/Codeception/Codeception/blob/2.2/src/Codeception/Module/AngularJS.php#L112)
* `nextTick` in Vue. See [JS Fiddle](https://jsfiddle.net/2vxdh6nw/).


* **HINT** write `waitForNextTick` method in AcceptanceTester to wait for Vue.nextTick() 
* **HINT** write `waitForAjaxRequests` in JQuery

```php
$I->waitForJS("return $.active == 0;", 60);
```

* Advanced: implement `waitForNextTick`, `waitForAjaxRequests` into Helper\Acceptance::afterStep, following AngularJS example.

---

## Follow Tests with Recorder

Enable `Codeception\Extension\Recorder` in `acceptance.suite.yml`:

```yaml
extensions:
    enabled:
        - Codeception\Extension\Recorder:
          delete_successful: false
```

And run the tests!

---

## Refactoring

Using Page Objects

```
./vendor/bin/codecept g:page Project
```


### Reusing Test Code

* Helpers - implement custom steps ([facebook/webDriver]() API)
* Actor - group common steps into actions
* PageObject - group common actions for a page


#### Helper

* Located in `tests/_support/Helper`
* Custom modules (can extend Codeception Modules)
* When Codeception Modules are not enough


#### Actor

* represents `$I`
* located in `tests/_support` 
* for acceptance tests it is **AcceptanceTester**
* includes proxy methods for modules (`tests/_support/_generated`)
* includes custom actions


```php
class AcceptanceTester extends \Codeception\Actor
{
    // proxy method to modules
    use _generated\AcceptanceTesterActions;

    public function logIn()
    {
        $this->amOnPage('/login');
        $this->submitForm('.special-form form', [
            'email' => 'admin@admin.com',
            'password' => 'adminsadmins'
        ]);
    }
}
```


#### PageObjects

* represent a page on a site
* contains locators (elements) of a page
* contains scenarios can be done on a page 



```php
namespace Page\Acceptance;

class Project
{
    // inject $I
    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    // locators
    public $taskList = '.task-list';

    // data
    public $defaultData = ['title' => 'taskXXX'];

    // logic
    public function createNewTask($projectId, $data = [])
    {
      // ....
    }
}
```


## Delete Task Refactored

* use PageObject to create new task
* stpore locators in PageObject
* store default data in PageObject
* implement method to get dynamic locators



```php
/**
 * @depends createTask
 */
public function deleteTask(AcceptanceTester $I, \Page\ProjectPage $project)
{
    $taskName = $project->addNewTask(2);
    $taskCSS = $project->getTaskElementCSS($taskName);
    $I->moveMouseOver($taskCSS);
    $I->seeElement("$taskCSS {$project->deleteButton}");
    $I->click("$taskCSS {$project->deleteButton}");
    $I->waitForElementVisible($project->popup);
    $I->click($project->popupConfirmButton);
    $I->waitForElementNotVisible($project->popup);
    $I->dontSee($taskName);
}
```


## Get Task ID from a Page

```php
public function getTaskElementCSS($taskName)
{
    $class = $this->acceptanceTester->grabAttributeFrom(
      Locator::contains($this.taskList . ' li', $title), 
      'class');

    if (preg_match('~task-(\d+?)~', $class, $matches)) {
        codecept_debug('Task Id: ' . $matches[1]);
        return $matches[1];
    }
    return null;
}
```


## Hide Implementation Complexity

```php
/**
 * @depends createTask
 */
public function deleteTask(AcceptanceTester $I, \Page\ProjectPage $project)
{
    $taskName = $project->addNewTask($this->currentProject);
    $projectPage->deleteTask($taskName);
    $I->dontSee($taskName);
}
```

---

## Lifehacks


### Codeception\Util\Locator

* `combine` - merge 2 locators
* `contains` - element that contains test
* `elementAt` - get nth element in scope
* `firstElement` - get the first element in scope
* `find` - get element by locator and attributes
* `href` - get link by href
* `lastElement` - get last element in scope
* `tabIndex` - get element by tab position


### Element At Position


```php
$I->click('Edit', Locator::elementAt('#data-grid tr', 3));
```

![](img/grid.png)


## Other Locators

* Locate element with text inside

```php
Locator::contains('div[contenteditable]',  'hello world');
```

* Combine different selectors

```php
$I->see('Title', Locator::combine('h1','h2','h3'));

```

* [and more](http://codeception.com/docs/reference/Locator)

---

## Session Snapshots

Don't login for each test

```php
if ($I->loadSessionSnapshot('login')) {
    return;
} // logged in
$I->amOnPage('/login');
$I->submitForm('#loginForm', [
    'login' => $name, 
    'password' => $password
]);
// saving snapshot
$I->saveSessionSnapshot('login');

```

---


### Wait For Elements

* waitForElement
* smartWait
* performOn


### SmartWait

* works with Id/CSS/XPath/Strict locators
* replaces `waitForElement`
* add `wait: 5` to config
* doesn't work with fuzzy locators

---

### Multi-Session Testing

```php
$joe = $I->haveFriend('joe');
$joe->does(function($I) {
   $I->amOnPage('/'); 
});
```

---

## Data Management

* Using Laravel ORM
* Using DB module
* Using REST API

---

## Practice: Test TodoMVC application

http://todomvc.com/examples/react/#/


### Tasks

* create todo
* edit todo
* delete todo
* clear completed
* filter todos