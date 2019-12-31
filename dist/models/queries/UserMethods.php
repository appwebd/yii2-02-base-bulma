<?php
/**
 * User methods
 * PHP version 7.2.0
 *
 * @category  Models
 * @package   User
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

namespace app\models\queries;

use app\models\User;
use Yii;

/**
 * Class UserMethods
 * PHP version 7.2.0
 *
 * @category  models
 * @package   User
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Commercial Private license
 * @version   Release: <package_id>
 * @link      https://appwebd.github.io
 * @date      2019-06-24
 *
 * @property int $userId
 */
class UserMethods extends User
{
    /**
     * Get model user given username
     *
     * @param int $userId primary key of table User
     *
     * @return string
     */
    public static function getUsername($userId)
    {
        $model = User::findOne([self::USER_ID => $userId]);
        if ($model !== null) {
            $return = $model->username;
        } else {
            $return = Yii::t('app', 'Unkown');
        }
        return $return;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return object|null
     */
    public function findByPasswordResetToken($token)
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne(
            [
                'password_reset_token' => $token
            ]
        );
    }

    /**
     * Get profile_id of user
     *
     * @param int $userId Primary key of table User
     *
     * @return bool|int
     */
    public function getProfileUser($userId)
    {
        $model = static::findOne($userId);
        if ($model !== null) {
            return $model->profile_id;
        }

        return false;
    }

    /**
     * Get the user_id of table user
     *
     * @return int user_id primary key of table user
     */
    public function getUserId()
    {
        return Yii::$app->user->isGuest ?
            User::USER_ID_VISIT
            :
            Yii::$app->user->identity->getId();
    }
}
