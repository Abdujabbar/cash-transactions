<?php

use yii\helpers\Url;

class LoginCest
{
    public function ensureThatLoginWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->see('Login', 'h1');

        $I->amGoingTo('try to login with correct credentials');
        $I->fillField('input[name="LoginForm[username]"]', 'qqqqqq');
        $I->click('login-button');
        sleep(2);

        $I->expectTo('see user info');
        $I->see('Logout');
    }

    public function ensureThatUsersWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/users/index'));
        $I->see('Users');
    }

    public function ensureThatTransfersWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/transfers/index');
        sleep(1);
        $I->amOnPage('/site/login');
    }
}
