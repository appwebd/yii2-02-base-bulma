<?php

namespace app\models\queries;

use app\models\User;
use Yii;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{

    /**
     * @return static the query with conditions for users that can login applied
     */
    public function canLogin()
    {
        return $this->andWhere(
            [
                ACTIVE => User::STATUS_ACTIVE,
                'email_is_verified' => 1,
            ]
        );
    }

    /**
     * @param $email
     * @return static the query with condition for given email applied
     */
    public function email($email)
    {
        return $this->andWhere(['email' => $email]);
    }

    /**
     * @param string $token the email confirmation token
     * @return static the query with conditions for valid email confirmation token applied
     */
    public function emailConfirmationToken($token)
    {
        $expire = Yii::$app->params['user.emailConfirmationTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return $this->andWhere('FALSE');
        }
        return $this->andWhere(['email_confirmation_token' => $token]);
    }

    /**
     * @param string $token the password reset token
     * @return static the query with conditions for valid password reset token applied
     */
    public function passwordResetToken($token)
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return $this->andWhere('FALSE');
        }
        return $this->andWhere(['password_reset_token' => $token]);
    }

    /**
     * @param $username
     * @return static the query with condition for given username applied
     */
    public function username($username)
    {
        return $this->andWhere(['username' => $username]);
    }
}
