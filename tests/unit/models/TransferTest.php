<?php
/**
 * Created by PhpStorm.
 * User: abdujabbor
 * Date: 6/26/18
 * Time: 3:12 PM
 */

namespace tests\models;


use app\models\Transfer;
use app\models\User;

class TransferTest extends \Codeception\Test\Unit
{
    protected $firstUser = 'firstUser';
    protected $secondUser = 'secondUser';

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

    }

    public function testSimpleTransfer()
    {
        $user1 = new User(['username' => $this->firstUser]);
        $this->assertEquals($user1->save(), true);
        $user2 = new User(['username' => $this->secondUser]);
        $this->assertEquals($user2->save(), true);
        $this->assertEquals(0, $user1->balance);
        $this->assertEquals(0, $user2->balance);
        $transfer = new Transfer();
        $transfer->setAuthUser($user1);
        $transfer->amount = 0.01;
        $transfer->username =  $user2->username;
        $transfer->setScenario(Transfer::SCENARIO_TRANSFER);
        $this->assertEquals($transfer->save(), true);
        $storedUser1 = User::findByUsername($this->firstUser);
        $storedUser2 = User::findByUsername($this->secondUser);
        $this->assertEquals($storedUser1->balance, -0.01);
        $this->assertEquals($storedUser2->balance, 0.01);
    }


    public function testMaxValueOfTransfer()
    {
        $user1 = new User(['username' => $this->firstUser]);
        $this->assertEquals($user1->save(), true);
        $user2 = new User(['username' => $this->secondUser]);
        $this->assertEquals($user2->save(), true);
        $this->assertEquals(0, $user1->balance);
        $this->assertEquals(0, $user2->balance);
        $transfer = new Transfer();
        $transfer->setAuthUser($user1);
        $transfer->amount = 10000;
        $transfer->username =  $user2->username;
        $transfer->setScenario(Transfer::SCENARIO_TRANSFER);
        $this->assertEquals($transfer->validate(), false);
        $this->arrayHasKey('amount', $transfer->getErrors());
    }


    public function testTransferToOwnself()
    {
        $user = new User(['username' => $this->firstUser]);
        $this->assertEquals($user->save(), true);
        $transfer = new Transfer();
        $transfer->setAuthUser($user);
        $transfer->amount = 1000;
        $transfer->username =  $user->username;
        $transfer->setScenario(Transfer::SCENARIO_TRANSFER);
        $this->assertEquals($transfer->validate(), false);
        $this->arrayHasKey('username', $transfer->getErrors());
    }

}