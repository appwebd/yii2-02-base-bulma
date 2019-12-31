<?php
/**
 * Contact Form
 * PHP Version 7.4.0
 *
 * @package   Contact
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2018-06-16 16:49:58
 */

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    const BODY = 'body';
    const EMAIL = 'email';
    const NAME = 'name';
    const SUBJECT = 'subject';
    const VERIFY_CODE = 'verifyCode';
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [[self::NAME, self::EMAIL, self::SUBJECT, self::BODY, self::VERIFY_CODE],REQUIRED],
            // email has to be a valid email address
            [self::EMAIL, self::EMAIL],
            // verifyCode needs to be entered correctly
            [self::VERIFY_CODE, 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        $translate = new Yii\i18n\I18N();

        return [
            self::NAME => $translate->translate('app', 'Name', [], Yii::$app->language),
            self::EMAIL => $translate->translate('app', 'Email', [], Yii::$app->language),
            self::SUBJECT => $translate->translate('app', 'Subject', [], Yii::$app->language),
            self::BODY => $translate->translate('app', 'Message', [], Yii::$app->language),
            self::VERIFY_CODE => $translate->translate('app', 'Verification Code', [], Yii::$app->language),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function sendEmail($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}
