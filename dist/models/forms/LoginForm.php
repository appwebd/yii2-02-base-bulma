<?php

namespace app\models\forms;

use app\models\queries\Common;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    const USERNAME = 'username';
    const PASSW0RD = 'password';
    const REMEMBER_ME = 'rememberMe';
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    /**
     * Removes email confirmation token and sets is_email_verified to true
     *
     * @param int $userId Primary key
     *
     * @return bool|null whether the save was successful or null if $save was false.
     */
    public static function removeTokenEmail($userId)
    {
        $status = false;
        $model = User::findIdentity($userId);
        if ($model !== null) {
            $model->email_confirmation_token = null;
            $model->email_is_verified = 1;
            $common = new Common();
            $status = $common->transaction($model, ACTION_DELETE);
        }
        return $status;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::USERNAME, self::PASSW0RD],REQUIRED], // username and password are both required

            // rememberMe must be a boolean value
            [self::REMEMBER_ME, 'boolean'],
            // password is validated by validatePassword()
            [self::PASSW0RD, 'validatePassword'],
            [self::USERNAME, 'validateUser'],
        ];
    }

    public function attributeLabels()
    {
        return [
            self::USERNAME => Yii::t('app', 'Username'),
            self::PASSW0RD => Yii::t('app', 'Password'),
            self::REMEMBER_ME => Yii::t('app', 'Remember me'),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Yii\web\IdentityInterface
     */
    final public function getUser()
    {
        if ($this->_user  === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Validation of User/pass and Administrative rules, for example cookie duration
     *
     * @return bool
     * @throws NotFoundHttpException
     */
    public function loginAdmin()
    {
        if ($this->validate()) {
            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? 3600 * 24 * 30 : 0
            );
        } else {
            throw new NotFoundHttpException('Something is wrong with your user/pass');
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    /**
     * Validates the user account.
     * This method serves as the inline validation for User account.
     */
    public function validateUser()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$this->getUser()) {
                $this->addError('username', 'Incorrect username or password.');
            }
        }
    }
}
