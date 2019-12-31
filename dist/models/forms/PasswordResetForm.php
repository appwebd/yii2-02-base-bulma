<?php
/**
 * Password reset form
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

namespace app\models\forms;

use app\controllers\BaseController;
use app\models\User;
use Exception;
use Yii;
use yii\base\Model;
use app\models\queries\Bitacora;

/**
 * User
 *
 * @property integer active                      Active
 * @property string password_hash               password
 * @property integer user_id                     User
 *
 */
class PasswordResetForm extends Model
{
    const PASSW0RD = 'passw0rd';
    const USER_ID = 'user_id';
    const TITLE = 'Users';

    public $passw0rd;
    public $user_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[
                self::PASSW0RD
            ],REQUIRED],

            [[self::PASSW0RD], STRING, LENGTH => [8, 255]],
            [[self::USER_ID], STRING],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            self::PASSW0RD => Yii::t('app', 'password'),
            self::USER_ID => Yii::t('app', 'user'),

        ];
    }

    /**
     * Update password
     *
     * @param object $modelForm @app\models\forms\PasswordResetForm
     *
     * @return bool
     * @throws Exception
     */
    public function passwordUpdate($modelForm)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $modelForm->load(Yii::$app->request->post());
            $userId  = BaseController::stringEncode($modelForm->user_id);
            $model = User::findOne($userId);
            if ($model !== null) {
                $model->setPassword($modelForm->passw0rd);
                $model->password_reset_token = null;
                if ($model->save()) {
                    $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            $event = Yii::t(
                'app',
                'Error, updating password {error}',
                [ ERROR => $exception]
            );
            $bitacora = new Bitacora();
            $bitacora->register($event, 'passwordUpdate', MSG_ERROR);
        }

        return false;
    }
}
