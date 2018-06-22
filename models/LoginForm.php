<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    protected $_user;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->_user = new User();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return ArrayHelper::merge([
            [['username'], 'required'],
        ], $this->_user->rules());
    }


    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->firstOrCreate();

        if ($this->validate()) {
            return Yii::$app->user->login($user, 60 * 60 * 24);
        }
        return false;
    }

    /**
     * @return array|bool|null|yii\web\IdentityInterface
     */
    public function firstOrCreate()
    {
        $user = User::findByUsername($this->username);
        if (!$user) {
            $user = new User();
            $user->username = $this->username;
            if ($user->save()) {
                return $user;
            }
        }

        return $user;
    }
}
