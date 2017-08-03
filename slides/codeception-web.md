
## Selenium WebDriver

* **WebDriver** is a protocol for browser control
* **Selenium** is a API server to manage browsers via WebDriver
* **ChromeDriver**, **MarionetteDriver** - drivers for browsers
* **SauceLabs, BrowserStack** - remote WebDriver servers
* **PhantomJS** chrome-based headless browser implementing WebDriver protocol

---

![](img/webdriver-chart.jpg)

---

## How to run browsers

* Window Mode
 - via Selenium Server
 - via ChromeDriver, MarionetteDriver
* Headless Mode
 - Headless Chrome
 - via Docker
 - with Xvfb (virtual framebuffer)
 - PhantomJS
* Remotely
 - cloud testing providers

---

## How to Locate Elements

* CSS *(most common)*
* XPath *(most powerful)*
* Button | Link Texts
* Field Names *(most stable)*
* Field Labels *(most readable)*


## Good Locators

* Short (ideally id of element)
* Doesn't rely on element's position
* Stable to changes



```php
$I->seeElement('#user'); // good
$I->seeElement('div>div>ul>li>span'); // bad
```

---

## What to check

* Text visibility on page
* Elements on page
* URLs on page

---

## Managing Asynchonity

* Wait for elements
* Wait for JavaScript
* SmartWait