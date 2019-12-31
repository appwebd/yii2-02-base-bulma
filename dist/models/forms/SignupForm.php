<?php
/**
 * SignUp form
 * PHP version 7.2.0
 *
 * @category  View
 * @package   User
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

namespace app\models\forms;

use app\helpers\Mail;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * SignUp form
 * PHP version 7.2.0
 *
 * @category  View
 * @package   User
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   Release: <package_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 *
 * @property integer active                      Active
 * @property string auth_key                    key auth
 * @property string email                       Email
 * @property string email_confirmation_token    Email token of confirmation
 * @property integer email_is_verified           Boolean is email verified
 * @property string firstName                   First Name
 * @property string lastName                    Last Name
 * @property string password_hash               password
 * @property string password_reset_token        password reset token
 * @property string password_reset_token_date   password reset token date creation
 * @property integer profile_id                  Profile
 * @property string telephone                   Phone number 12 digits
 * @property integer user_id                     User
 * @property string username                    User account
 * @property string ipv4_address_last_login     Ipv4 address of last login
 */
class SignupForm extends Model
{
    const EMAIL = 'email';
    const FIRST_NAME = 'firstName';
    const ICON = 'fas fa-user';
    const LAST_NAME = 'lastName';
    const STRING =STRING;
    const USERNAME = 'username';
    const PASSW0RD = 'password';
    const NEW_PASSW0RD = 'password';
    const USER_ACTIVE = 1;
    const PROFILE_USER = 20;
    const EMAIL_IS_VERIFIED_FALSE = 0;

    /**
     * Username or account for user login
     *
     * @var string $username
     */
    public $username;
    /**
     * Email
     *
     * @var string $email
     */
    public $email;
    /**
     * Password
     *
     * @var string $password
     */
    public $password;

    /**
     * First Name
     *
     * @var string $firstName
     */
    public $firstName;

    /**
     * Last Name
     *
     * @var string $lastName
     */
    public $lastName;

    /**
     * Rules of model
     *
     * @return array
     */
    public function rules()
    {
        return [
            [[
                self::EMAIL,
                self::FIRST_NAME,
                self::LAST_NAME,
                self::USERNAME,
                self::PASSW0RD],REQUIRED],
            [[
                self::EMAIL,
                self::FIRST_NAME,
                self::LAST_NAME,
                self::USERNAME], 'trim'],

            [
                self::USERNAME, 'unique', 'targetClass' => User::class
            ],
            [self::USERNAME, self::STRING, 'min' => 2, 'max' => 255],

            [self::EMAIL, 'email'],
            [self::EMAIL, 'unique', 'targetClass' => User::class],

            [self::FIRST_NAME, STR_DEFAULT, VALUE => ''],
            [self::LAST_NAME, STR_DEFAULT, VALUE => ''],
            [self::FIRST_NAME, self::STRING, LENGTH => [1, 80]],
            [self::LAST_NAME, self::STRING, LENGTH => [1, 80]],

            [self::PASSW0RD, self::STRING, LENGTH => [5, 254]],
        ];
    }

    /**
     * Signs up new user
     *
     * @return mixed app\model\User|null the saved user model or null if saving fails
     */
    public function singup()
    {
        if (!$this->_checkFromEmail() || !$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->email_is_verified = SignupForm::EMAIL_IS_VERIFIED_FALSE;
        $user->email_confirmation_token = null;
        $user->firstName = $this->firstName;
        $user->lastName = $this->lastName;
        $user->telephone = '';
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->genEmailConfToke(true); //Generate email Confirmation Token
        $user->profile_id = SignupForm::PROFILE_USER; // 20: Usuario comun
        $user->ipv4_address_last_login = Yii::$app->getRequest()->getUserIP();
        $user->active = SignupForm::USER_ACTIVE;
        $user->genPassResetToke(true); //generatePasswordResetToken

        if ($user->validate() && $user->save()) {
            Yii::info("OK your account was saved.", __METHOD__);
            $subject = Yii::t('app', 'Signup email of confirmation');
            if (!Mail::sendEmail($user, $subject, 'user/confirm-email')) { // app/mail/user/confirm-email-html.php
                Yii::$app->session->setFlash(
                    ERROR,
                    Yii::t(
                        'app',
                        'Failed to send confirmation email to new user.'
                    )
                );
            }
            return $user;
        }

        $message = Yii::t(
            'app',
            'Could not save new User:\n'
        )
        . print_r($user->errors, true);
        Yii::$app->session->setFlash(ERROR, $message);
        return null;
    }

    /**
     * Check if was defined params adminEmail
     * in the file @app/config/params.php variable adminEmail.
     *
     * @return true|false
     */
    private function _checkFromEmail()
    {
        $from = Yii::$app->params['adminEmail'];
        if ($from == "email@example.com") {
            Yii::warning(
                Yii::t(
                    'app',
                    'config/params.php is incomplete (adminEmail is missing)'
                ),
                __METHOD__
            );
            return false;
        }

        return true;
    }
}
