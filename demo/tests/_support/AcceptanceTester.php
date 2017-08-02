<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * @Given I am blog administrator
     */
     public function iAmBlogAdministrator()
     {
        throw new \Codeception\Exception\Incomplete("Step `I am blog administrator` is not defined");
     }

    /**
     * @When I enter admin dashboard
     */
     public function iEnterAdminDashboard()
     {
        throw new \Codeception\Exception\Incomplete("Step `I enter admin dashboard` is not defined");
     }

    /**
     * @When I create new post :arg1 :arg2
     */
     public function iCreateNewPost($arg1, $arg2)
     {
        throw new \Codeception\Exception\Incomplete("Step `I create new post :arg1 :arg2` is not defined");
     }

    /**
     * @When I publish it
     */
     public function iPublishIt()
     {
        throw new \Codeception\Exception\Incomplete("Step `I publish it` is not defined");
     }

    /**
     * @Then I should see it on site
     */
     public function iShouldSeeItOnSite()
     {
        throw new \Codeception\Exception\Incomplete("Step `I should see it on site` is not defined");
     }
}
