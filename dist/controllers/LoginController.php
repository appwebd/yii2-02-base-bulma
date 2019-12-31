<?php
/**
 * Class LoginController
 * PHP Version 7.0.0
 *
 * @category  Controller
 * @package   Login
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   Release: <package_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 10:07 PM
 */
namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class LoginController
 * PHP Version 7.0.0
 *
 * @category  Controller
 * @package   Login
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   Release: <package_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 10:07 PM
 */
class LoginController extends BaseController
{
    const LOGOUT = 'logout';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                STR_CLASS => AccessControl::class,
                'only' => [ACTION_INDEX, self::LOGOUT],
                'rules' => [
                    [
                        ALLOW => true,
                        ACTIONS => [ACTION_INDEX],
                        ROLES => ['?'],
                    ],
                    [
                        ALLOW => true,
                        ACTIONS => [self::LOGOUT],
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                STR_CLASS => VerbFilter::class,
                ACTIONS => [
                    ACTION_INDEX => ['get', 'post'],
                    self::LOGOUT => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string|object \yii\web\Response the login form or a redirect response
     */
    public function actionIndex()
    {
        $headers = Yii::$app->response->headers;

// set a Pragma header. Any existing Pragma headers will be discarded.
        $headers->set('Pragma', 'no-cache');

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render(ACTION_INDEX, [MODEL => $model,]);
    }

    /**
     * @return object \yii\web\Response a redirect response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * @param $token string encoded of email confirmation token
     * @return string|object \yii\web\Response the confirmation failure message or a
     * redirect response
     */
    public function actionConfirmemail($token)
    {

        $token = BaseController::stringDecode($token);
        $model = User::find()->emailConfirmationToken($token)->one();

        if ($model !== null && LoginForm::removeTokenEmail($model->user_id)) {
            Yii::$app->getUser()->login($model);
            return $this->goHome();
        }

        return $this->render('email-confirmation-failed');
    }
}
