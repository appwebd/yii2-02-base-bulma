<?php
/**
 * Users
 * PHP Version 7.4.0
 *
 * @package   Users
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2018-06-16 16:49:58
 */

namespace app\models;

use app\models\queries\Bitacora;
use app\models\queries\UserQuery;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\HtmlPurifier;
use yii\web\IdentityInterface;

/**
 * User
 *
 * @property integer active                      Active
 * @property string auth_key                    key auth
 * @property string email                       Email
 * @property string email_confirmation_token    Email token of confirmation
 * @property integer email_is_verified           Boolean is email verified
 * @property string firstName                   First Name
 * @property string ipv4_address_last_login     ipv4 address of last login
 * @property string lastName                    Last Name
 * @property string password_hash               password
 * @property string password_reset_token        password reset token
 * @property string password_reset_token_date   password reset token date creation
 * @property integer profile_id                  Profile
 * @property string telephone                   Phone number 12 digits
 * @property integer user_id                     User
 * @property string username                    User account
 */

class User extends ActiveRecord implements IdentityInterface
{
    const ACTIVE = 'active';
    const AUTH_KEY = 'auth_key';
    const EMAIL = 'email';
    const EMAIL_CONFIRMATION_TOKEN = 'email_confirmation_token';
    const EMAIL_IS_VERIFIED = 'email_is_verified';
    const EMAIL_IS_VERIFIES_VALUE = 1;
    const FIRSTNAME = 'firstName';
    const IPV4_ADDRESS_LAST_LOGIN = 'ipv4_address_last_login';
    const LASTNAME = 'lastName';
    const PASSW0RD_HASH = 'password_hash';
    const PASSW0RD_RESET_TOKEN = 'password_reset_token';
    const PASSW0RD_RESET_TOKEN_DATE = 'password_reset_token_date';
    const PROFILE_USER = 20;
    const PROFILE_VISIT = 0;
    const PROFILE_ID = 'profile_id';
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    const STATUS_FALSE = 0;
    const STATUS_TRUE = 1;
    const TELEPHONE = 'telephone';
    const TITLE = 'Users';
    const USERNAME = 'username';
    const USER_ID = 'user_id';
    const USER_ID_VISIT = 1;
    const ICON = ' user';

    /**
     * @var string|null the current password value from form input
     */

    public $_password;
    public $password;

    /**
     * @return UserQuery custom query class with user scopes
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }


    /**
     * Find user by AccessToken
     *
     * @param $token string
     *
     * @param null $type
     * @return User
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne([self::AUTH_KEY => $token]);
    }

    /**
     * Find Identity
     *
     * @param int $userId Primary key of table user
     * @return User
     */
    public static function findIdentity($userId)
    {
        return static::findOne([self::USER_ID => $userId, self::ACTIVE => self::STATUS_ACTIVE]);
    }


    /**
     * Get the user_id of table user
     * @return int user_id primary key of table user
     */
    public static function getIdentityUserId()
    {
        return Yii::$app->user->isGuest ? User::USER_ID_VISIT : Yii::$app->user->identity->getId();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne([self::USERNAME => $username, self::ACTIVE => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ACTIVE,
                self::AUTH_KEY,
                self::EMAIL,
                self::EMAIL_IS_VERIFIED,
                self::FIRSTNAME,
                self::LASTNAME,
                self::PASSW0RD_HASH,
                self::PROFILE_ID,
                self::USERNAME], 'required'],

            [self::AUTH_KEY, STRING, LENGTH => [1, 32]],
            [self::EMAIL, 'email'],
            [[self::EMAIL, self::USERNAME], 'unique'],
            [self::EMAIL_CONFIRMATION_TOKEN, STRING, LENGTH => [1, 255]],
            [self::EMAIL_CONFIRMATION_TOKEN, 'unique'],
            [self::FIRSTNAME, STRING, LENGTH => [1, 80]],
            [self::LASTNAME, STRING, LENGTH => [1, 80]],
            [self::PASSW0RD_HASH, STRING, LENGTH => [1, 255]],
            [self::PASSW0RD_RESET_TOKEN, STRING, 'max' => 255],
            [[self::PROFILE_ID], 'in', 'range' => array_keys(Profile::getProfileList())],
            [self::TELEPHONE, STRING, 'max' => 15],
            [[self::USERNAME, self::IPV4_ADDRESS_LAST_LOGIN], STRING, LENGTH => [1, 20]],
            [[
                self::PROFILE_ID,
                self::USER_ID], 'integer'],
            [[self::ACTIVE,
                self::EMAIL_IS_VERIFIED], 'boolean'],
            [[self::AUTH_KEY,
                self::EMAIL,
                self::FIRSTNAME,
                self::LASTNAME,
                self::PASSW0RD_HASH,
                self::PASSW0RD_RESET_TOKEN,
                self::TELEPHONE,
                self::USERNAME], 'trim'],
            [[self::AUTH_KEY,
                self::EMAIL,
                self::FIRSTNAME,
                self::LASTNAME,
                self::PASSW0RD_HASH,
                self::PASSW0RD_RESET_TOKEN,
                self::TELEPHONE,
                self::USERNAME], function ($attribute) {
                    $this->$attribute = HtmlPurifier::process($this->$attribute);
                }
            ],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            self::ACTIVE => Yii::t('app', 'Active'),
            self::AUTH_KEY => Yii::t('app', 'key auth'),
            self::EMAIL => Yii::t('app', 'Email'),
            self::EMAIL_CONFIRMATION_TOKEN => Yii::t('app', 'Email token of confirmation '),
            self::EMAIL_IS_VERIFIED => Yii::t('app', 'Boolean is email verified '),
            self::FIRSTNAME => Yii::t('app', 'First name'),
            self::IPV4_ADDRESS_LAST_LOGIN => Yii::t('app', 'Last ipv4 address used'),
            self::LASTNAME => Yii::t('app', 'Last name'),
            self::PASSW0RD_HASH => Yii::t('app', 'password'),
            self::PASSW0RD_RESET_TOKEN => Yii::t('app', 'password reset token'),
            self::PASSW0RD_RESET_TOKEN_DATE => Yii::t('app', 'password reset token date creation'),
            self::PROFILE_ID => Yii::t('app', 'Profile'),
            self::TELEPHONE => Yii::t('app', 'Phone number'),
            self::USERNAME => Yii::t('app', 'User account'),
            self::USER_ID => Yii::t('app', 'user'),

        ];
    }

    /**
     * behaviors
     * The fields created_at, updated_at, password_reset_token_date should not
     * be declared as required in rules.
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at', self::PASSW0RD_RESET_TOKEN_DATE],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */

    public function getProfile()
    {
        return $this->hasOne(
            Profile::className(),
            [self::PROFILE_ID => self::PROFILE_ID]
        );
    }

    /**
     * Get primary key id
     *
     * @return integer primary key
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        try {
            $security = new yii\base\Security();
            $this->auth_key = $security->generateRandomString();
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register($exception, 'app\models\User\generateAuthKey', MSG_ERROR);
        }
    }

    /**
     * Generates new password reset token
     *
     * @param bool $save whether to save the record. Default is `false`.
     *
     * @return bool|null whether the save was successful or null if $save was false.
     */
    public function genPassResetToke($save)
    {
        try {
            $security = new yii\base\Security();
            $this->auth_key = $security->generateRandomString();
            $this->password_reset_token = $security->generateRandomString() . '_' . time();
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\User::generatePasswordResetToken',
                MSG_ERROR
            );
        }

        if ($save) {
            return $this->save();
        }
        return false;
    }

    /**
     * Generates new email confirmation token
     *
     * @param bool $save whether to save the record. Default is `false`.
     *
     * @return bool|null whether the save was successful or null if $save was false.
     */
    public function genEmailConfToke($save)
    {
        try {
            $security = new yii\base\Security();
            $this->email_confirmation_token = $security->generateRandomString() . '_' . time();
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\User::generateEmailConfirmationToken',
                MSG_ERROR
            );
        }
        if ($save) {
            return $this->save();
        }
        return false;
    }



    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        if (!empty($password)) {
            try {
                $security = new yii\base\Security();
                $this->password_hash = $security->generatePasswordHash($password);
            } catch (Exception $exception) {
                $bitacora = new Bitacora();
                $bitacora->register($exception, 'app\models\User::setPassword', MSG_ERROR);
            }
        }
    }

    /**
     * @validateAuthKey
     *
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @getAuthKey
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $security = new yii\base\Security();
        return $security->validatePassword($password, $this->password_hash);
    }
}
