<?php

namespace tests\models;
use Yii;
use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
    protected $username = 'admincha';
    protected $notValidUsername = 'not-admin';


    public function testCreateUser()
    {
        $user = new User();
        $user->username = $this->username;
        $this->assertEquals($user->save(), true);
        $this->assertEquals(true, $user->id > 0);
        $storedUser = User::findByUsername($this->username);
        expect_that($storedUser);
        $this->assertEquals($storedUser->balance, 0);
    }

    public function testDuplicateForUsername()
    {
        $user = new User();
        $user->username = $this->username;
        $this->assertEquals($user->save(), true);


        $user1 = new User();
        $user1->username = $this->username;
        $user1->setScenario(User::SCENARIO_INSERT);

        $this->assertEquals(false, $user1->validate());
    }
}
