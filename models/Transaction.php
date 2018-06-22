<?php

namespace app\models;

use Yii;
use app\components\validators\Transfer;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $from
 * @property int $to
 * @property int $amount
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $fromUser
 * @property User $toUser
 */
class Transaction extends \yii\db\ActiveRecord
{
    const SCENARIO_INSERT = 'insert';
    protected $authUser;
    public $username;
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        if(!Yii::$app->user->isGuest) {
            $this->setAuthUser(User::findByUsername(Yii::$app->user->getIdentity()->username));
        }
    }



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'amount'], 'required', 'scenario' => self::SCENARIO_INSERT],
            [['from', 'to', 'amount'], 'integer'],
            [['created_at', 'updated_at', 'from', 'to', 'amount'], 'safe'],
            [['from'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['from' => 'id']],
            [['to'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['to' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        return $this->hasOne(User::className(), ['id' => 'from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToUser()
    {
        return $this->hasOne(User::className(), ['id' => 'to']);
    }

    /**
     * {@inheritdoc}
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $transfer = new Transfer($this->authUser, $this->amount, $this->username);

        if($transfer->validate() && parent::beforeValidate()) {
            return true;
        }
        $this->addErrors($transfer->getErrors());
        return false;
    }

    public function setAuthUser(User $user) {
        $this->authUser = $user;
    }
}
