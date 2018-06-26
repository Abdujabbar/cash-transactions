<?php

use yii\helpers\Url;

class HomeCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));        
        $I->see('Navigate to top bar');
        
        $I->seeLink('Users');
        $I->click('Users');
        sleep(2);
        
        $I->see('Users');
    }
}
