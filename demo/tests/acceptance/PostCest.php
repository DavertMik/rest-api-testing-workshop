<?php


class PostCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/wp-admin');
        $I->submitForm('#loginform', [
            'log' => 'admin',
            'pwd' => 'admins'
        ]);
        $I->see('Dashboard', 'h1');        
    }

    /**
     * @depends LoginCest:shouldLoginSuccessfully
     */
    public function createNewPost(AcceptanceTester $I)
    {
        $I->click('Write your first blog post');
        $I->see('Add New Post', 'h1');
        $I->submitForm('#post', [
            'post_title' => sq('post'),
            'content' => 'It is very interesting, please read it'
        ]);
        $I->see('Post updated', '#message');
        $I->see('Status: Draft');
        $I->click('All Posts');
        $I->see(sq('post') . ' — Draft');
    }

    public function publishNewPost(AcceptanceTester $I)
    {
        $I->click('Write your first blog post');
        $I->see('Add New Post', 'h1');
        $I->submitForm('#post', [
            'post_title' => sq('post'),
            'content' => 'It is very interesting, please read it'
        ], 'publish');
        $I->see('Post published', '#message');
        $I->see('Status: Published');
        $I->click('All Posts');
        $I->see(sq('post'));
    }    

    public function seePublishedPost(AcceptanceTester $I)
    {
        $I->click('Write your first blog post');
        $I->see('Add New Post', 'h1');
        $I->submitForm('#post', [
            'post_title' => sq('post'),
            'content' => 'It is very interesting, please read it'
        ], 'publish');
        $I->see('Post published', '#message');
        $I->click('#sample-permalink');
        $I->see(sq('post'), 'h1');
        $I->see('It is very interesting, please read it');
    }    
    
}
