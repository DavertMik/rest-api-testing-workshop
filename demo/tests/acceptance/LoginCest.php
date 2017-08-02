<?php
class LoginCest
{
    public function shouldLoginSuccessfully(AcceptanceTester $I)
    {
        $I->amOnPage('/wp-admin');
        $I->fillField('Username or Email Address', 'admin');
        $I->fillField('Password', 'admins');        
        $I->click('Log In');
        $I->see('Dashboard', 'h1');
    }

    public function shouldLoginSuccessfully2(AcceptanceTester $I)
    {
        $I->amOnPage('/wp-admin');
        $I->fillField('log', 'admin');
        $I->fillField('pwd', 'admins');
        $I->click('Log In');
        $I->see('Dashboard', 'h1');
    }

    public function shouldLoginSuccessfully3(AcceptanceTester $I)
    {
        $I->amOnPage('/wp-admin');
        $I->submitForm('#loginform', [
            'log' => 'admin',
            'pwd' => 'admins'
        ]);
        $I->see('Dashboard', 'h1');
    } 

}
