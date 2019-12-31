<?php
/**
 * Class User
 * PHP version 7.0.0
 *
 * @category  Controller
 * @package   User
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

namespace app\controllers;

use app\components\DeleteRecord;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\search\UserSearch;
use app\models\User;
use Exception;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UserController
 * PHP Version 7.0
 *
 * @category  Controller
 * @package   User
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license   Private license
 */
class UserController extends BaseController
{
    const USER_ID = 'user_id';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action action name
     *
     * @return mixed \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($this->checkBadAccess($action->id)) {
            return $this->redirect(['/']);
        }

        $bitacora = new Bitacora();
        $bitacora->register(
            Yii::t(
                'app',
                'showing the view'
            ),
            'app\controller\UserController::beforeAction',
            MSG_INFO
        );
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function behaviors()
    {
        return $this->behaviorsCommon();
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('User');
            $model->email_is_verified = false;
            $model->email_confirmation_token = null;
            $model->setPassword($request['password']);
            $model->generateAuthKey();
            $model->ipv4_address_last_login = Yii::$app->getRequest()->getUserIP();

            $model->genEmailConfToke(true);
            $this->_saveRecord($model);
        }

        return $this->render(
            ACTION_CREATE,
            [
                MODEL => $model,
                'titleView' => 'Create'
            ]
        );
    }

    /**
     * Save User record
     *
     * @param object $model app\models\User
     *
     * @return bool|Response
     */
    private function _saveRecord($model)
    {
        try {
            $common = new Common();
            $status = $common->transaction($model, STR_SAVE);
            $this->saveReport($status);
            if ($status) {
                $privateKey = BaseController::stringEncode($model->user_id);
                return $this->redirect([ACTION_VIEW, 'id' => $privateKey]);
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash($exception, '_saveRecord', MSG_ERROR);
        }
        return false;
    }

    /**
     * Deletes an existing row of User model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param integer $id primary key iof table user
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $deleteRecord = new DeleteRecord();
        if (!$deleteRecord->isOkPermission(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);
        if ($this->fkCheck($model->user_id) > 0) {
            $deleteRecord->report(2);
            return $this->redirect([ACTION_INDEX]);
        }

        try {
            $common = new Common();
            $status = $common->transaction($model, ACTION_DELETE);
            $deleteRecord->report($status);
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash($exception, 'actionDelete', MSG_ERROR);
        }
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $privateKey primary key of table user (encrypted value)
     *
     * @return object User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($privateKey)
    {

        $privateKey = BaseController::stringDecode($privateKey);
        $model = User::findOne($privateKey);
        if ($model !== null) {
            return $model;
        }

        $event = Yii::t(
            'app',
            'The requested page does not exist {id}',
            ['id' => $privateKey]
        );

        $bitacora = new Bitacora();
        $bitacora->registerAndFlash($event, 'findModel', MSG_SECURITY_ISSUE);

        throw new NotFoundHttpException(
            Yii::t(
                'app',
                'The requested page does not exist.'
            )
        );
    }

    /**
     * Check nro. records found in other tables related.
     *
     * @param integer $userId int Primary Key of table User
     *
     * @return integer numbers of rows in other tables (integrity referential)
     */
    private function fkCheck($userId)
    {
        $common = new Common();
        return $common->getNroRowsForeignkey(
            'logs',
            self::USER_ID,
            $userId
        );
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $smUser = new UserSearch();
        $dmUser = $smUser->search(
            Yii::$app->request->queryParams
        );

        $pageSize = $this->pageSize();
        $dmUser->pagination->pageSize = $pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'searchModelUser' => $smUser,
                'dataProviderUser' => $dmUser,
                'pageSize' => $pageSize
            ]
        );
    }

    /**
     * Delete many records of this table User
     *
     * @return mixed
     */
    public function actionRemove()
    {
        $result = Yii::$app->request->post('selection');
        $deleteRecord = new DeleteRecord();

        if (!$deleteRecord->isOkPermission(ACTION_DELETE)
            || !$deleteRecord->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $nroSelections = sizeof($result);
        $status = [];
        // 0: OK was deleted,      1: KO Error deleting record,
        // 2: Used in the system,  3: Not found record in the system

        for ($counter = 0; $counter < $nroSelections; $counter++) {
            try {
                $privateKey = $result[$counter];
                $model = User::findOne($privateKey);
                $fk_check = $this->fkCheck($privateKey);
                $item = $deleteRecord->remove($model, $fk_check);
                $status[$item] .= $privateKey . ',';
            } catch (Exception $exception) {
                $bitacora = new Bitacora();
                $bitacora->registerAndFlash(
                    $exception,
                    'actionRemove',
                    MSG_ERROR
                );
            }
        }

        $deleteRecord->summaryDisplay($status);
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table user
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $this->_saveRecord($model);
        }

        return $this->render(
            ACTION_CREATE,
            [
                MODEL => $model,
                'titleView' => 'Update'
            ]
        );
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id primary key of table user
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $event = Yii::t('app', 'view record {id}', ['id' => $model->user_id]);
        $bitacora = new Bitacora();
        $bitacora->register($event, 'actionView', MSG_INFO);

        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
