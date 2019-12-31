<?php
/**
 * Class Mail
 * PHP Version 7.2
 *
 * @category  Helpers
 * @package   Mail
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      29/10/2019 12:13 PM
 */
namespace app\helpers;

use Yii;
use Swift_SwiftException;
use app\models\queries\Bitacora;

/**
 * A helper class for sending mails
 */
class Mail
{

    /**
     * Send an user email
     *
     * The view is rendered from `@app/mails/user` with the
     * `@app/mails/layouts/user` layout applied.
     *
     * The sender can be specified in `$params['from']` and defaults to the
     * `mail.from` application parameter.
     *
     * @param object $user    key of table user
     * @param string $subject subject of email
     * @param string $view    name of the view file to render from `@app/mail/user`
     *
     * @return bool whether the mail was sent successfully
     */
    public static function sendEmail($user, $subject, $view)
    {

        try {
            $bitacora = new Bitacora();
            $from   = Yii::$app->params['adminEmail'];
            $sendOK = Yii::$app
                ->mailer
                ->compose(
                    ['html' => $view.'-html', 'text' => $view.'-text'],
                    [
                        'model' => $user
                    ]
                )
                ->setFrom($from)
                ->setTo($user->email)
                ->setSubject($subject)
                ->send();

            $message = Yii::t(
                'app',
                'Subscription: Successful mail delivery:'. $user->email
            );
            $statusID = \MSG_SUCCESS;
            if (!$sendOK) {
                $message = Yii::t(
                    'app',
                    'Subscription mail delivery failed:'. $user->email
                );
                $statusID = \MSG_ERROR;
                Yii::warning($message, __METHOD__); // for debug purposes
            }
            $bitacora->register(
                $message,
                'app/helpers/Mail::sendEmail',
                $statusID
            );
            return $sendOK;
        } catch (Swift_SwiftException $exception) {
            $type = get_class($exception);
            $message = $exception->getMessage();
            $trace = $exception->getTraceAsString();
            $message = "Swift exception $type:\n$message\n\n$trace";
            Yii::warning($message, __METHOD__); // for debug purposes

            $bitacora = new Bitacora();
            $bitacora->register(
                $message,
                'app/helpers/Mail::sendEmail catch',
                MSG_ERROR
            );
        }

        return false;
    }
}
