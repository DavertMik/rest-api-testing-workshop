## Testing Business Expectations

---

### Why Do We Test 

* To ensure software works as expected
* To discover bugs in software (before users)
* To measure performance 
* To seek for security issues

---

> We automate tests to execute them at any time

---

### What Tests We Can Automate

* **To ensure software works as expected**
* ~~To discover bugs in software (before users)~~
* **To measure performance** (not covered in this training)
* ~~To seek for security issues~~

---

### Automated Testing

* To establish trust
* For constant changes
* To stabilize current codebase

---

### No Automated Testing When

* Features need to be delivered as fast as possible
* Project won't be changed in future
* Management is not interested in automated testing

---

### What Is Hard To Test

* Asynchronous interactions (JavaScript, Queues)
* 3rd party APIs
* Real data
* Captchas

---

## What to Test

---

### Priority First

* Crucial **business scenarios**
* Algorithms, functions with complex logic
* Everything that is hard to test manually

---

### What is in a Test

* Arrange (Given)
* Act (When)
* Assert (Then)

---

### Start With General


```gherkin
Feature: customer registration

Background:
  Given I am unregistered customer

Scenario: registering successfully
  When I register
  Then I should be registered
```

---

## Add Details

```gherkin
Scenario: registering successfully
  When I register with
    | Name      | davert              |
    | Email     | davert@sdclabs.com  |
    | Password  | 123456              |

  Then I should be registered
  And I receive confirmation email

```

---

## How to Test

---

### Outer and Inner testing

* **Outer**: test from the public interface
* **Inner**: test from the source code

---

> Think how you can test a feature with minimal effort

---

![](/img/pyramid.png)

---

![](/img/test-layers.png)

---

> The more specific feature we need to test the more detailed layer we choose.

---

## New Project. How to test?

* Domain Layer should have unit / integration tests
* Application layer should have integration / functional tests
* UI should have acceptance tests with positive scenarios

---

![](img/onion.png)

---

## Legacy Project. How to test?

* Detect the critical parts of a system
* Write acceptance tests for them
* Refactor old code
* Cover the new code with unit tests

---

## Know your options!

* Automated tests != unit tests
* Execution speed is not the only option
* **Stability** is important

---

![](/img/pros-cons.svg)

---

### How to write stable tests

* Don't mix specification with implementation
* Use public interfaces (API) for tests

[Blogpost: Expectation vs Implementation](http://codeception.com/12-21-2016/writing-better-tests-expectation-vs-implementation.html)

---

### Questions To Be Asked

* Will the test have to duplicate exactly the application code?
* Will assertions in the test duplicate any behavior covered by library code?
* Is this detail important, or is it only an internal concern?

[Blogpost: The Right Way To Test React Components](https://medium.freecodecamp.org/the-right-way-to-test-react-components-548a4736ab22)

---

### Example

Specification that doesn't survive the test of time: 

```gherkin
Given I am drunk 
And I am in a pub
And I want to get home
When I order a cab
Then I expect to see 2 horses and carriage
And they bring me home
```

---

## BDD

* Ubiquotous language for management/developers/QA
* Abstraction over test implementation
* Standard format for test scenarios (Gherkin)

---

![](img/3amigos.jpg)

---

### Format

```gherkin
Feature: Some important business feature
  In order to achieve my goal
  As a user
  I want to do something

Scenario: Use case of a feature
  Given some situation
  When an action is performed
  Then an outcome is expected
```

---

### Use BDD to

* To communicate between teams
* To make a test plan
* To use tests as live documentation
* To write abstract scenarios

---

### BDD Tradeoffs

* Might be over abstracted
* Requires implementation for all steps
* Lots of boilerplate code under hood
* One test is split into separate files 
* No one might be interested in reading scenarios

---

### BDD Anti-Patterns

* Lots of user interface details
* Describing actions using the personal pronounce
* Documenting boring scenarios
* Keeping all scenarios forever
* No clear separation between Given/When/Then
* Hard to tell what you are testing
* Writing all tests as BDD scenarios

[by John Ferguson Smart Limited](https://www.slideshare.net/wakaleo/bdd-antipatterns) 

---

### Bug != Feature

* Not all tests should be written in BDD manner
* Regression tests (for bugs) are not a feature
* Regression tests are not part of live documentation
* Regression tests is additional mess to scenarios

---

### Workflow

1. write down features of application
1. write scenarios for all features
1. add details and examples to scenarios
1. choose suitable testing layer for each scenario
1. write tests for the scenarios!

