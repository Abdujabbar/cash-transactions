<?php
/**
 * Created by PhpStorm.
 * User: abdujabbor
 * Date: 6/26/18
 * Time: 5:21 PM
 */

namespace app\models;


use yii\base\Model;

class TransferForm extends Model
{
    public $receiver;
    public $amount;
    /**
     * @var User
     */
    protected $receiverUser;
    /**
     * @var \yii\web\IdentityInterface
     */
    protected $senderUser;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->senderUser = User::findOne(\Yii::$app->user->getId());
    }

    public function rules()
    {
        return [
            [
                'receiver',
                'receiverExists'
            ],
            [
                [
                    'receiver',
                    'amount'
                ],
                'required'
            ],
            [
                'receiver',
                'string',
                'min' => 6,
            ],
            [
                'receiver',
                'match',
                'pattern' => '/^[a-zA-Z]*$/',
                'message' => 'receiver username can contain only characters'
            ],
            [
                [
                    'amount'
                ],
                'number',
                'min' => 0.01,
                'numberPattern' => '/^[\d]*+(.\d{1,2})?$/',
                'message' => 'amount must be a number, and can contain only can 2 decimals after floating point, float point character is "."'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'receiver' => 'Receiver username',
            'amount' => 'Amount',
        ];
    }


    public function afterValidate()
    {

        parent::afterValidate();
        if(!$this->hasErrors()) {
            $transfer = new Transfer();
            $transfer->sender = $this->senderUser->getId();
            $transfer->receiver = $this->receiverUser->getId();
            $transfer->amount = $this->amount;
            if (!$transfer->save()) {
                $this->addErrors($transfer->getErrors());
            }
        }
    }

    public function receiverExists($attribute, $params, $validator)
    {
        $this->receiverUser = User::findByUsername($this->$attribute);

        if (is_null($this->receiverUser)) {
            $this->addError($attribute, 'User not found');
            return false;
        }
        return true;
    }
}