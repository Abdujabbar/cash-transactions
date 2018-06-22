<?php

namespace tests\models;
use Yii;
use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
    protected $username = 'admin1234';
    protected $notValidUsername = 'not-admin';


    public function testCreateUser()
    {
        $user = new User();
        $user->username = $this->username;
        $this->assertEquals($user->save(), true);
        $this->assertEquals(true, $user->id > 0);
        expect_that(User::findByUsername($this->username));
    }

    public function testIsCreatedUserBalanceZero() {
        $user = new User();
        $user->username = $this->username;
        $user->save();
        $storedUser = User::findByUsername($this->username);
        $this->assertEquals($storedUser->balance, $user->balance);
    }
}
