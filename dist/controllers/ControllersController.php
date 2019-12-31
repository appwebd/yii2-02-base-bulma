<?php
/**
 * Controllers
 * PHP Version 7.2.0
 *
 * @category  Controllers
 * @package   Controllers
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @release   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:03
 */

namespace app\controllers;

use app\components\DeleteRecord;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\Controllers;
use app\models\search\ControllersSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Controllers
 *
 * @category Controller
 * @package  Controllers
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  Private license
 * @link     https://appwebd.github.io
 */
class ControllersController extends BaseController
{
    const CONTROLLER_ID = 'controller_id';


    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action Action Name
     *
     * @return bool|\yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    final public function beforeAction($action)
    {
        if ($this->checkBadAccess($action->id)) {
            return $this->redirect(['/']);
        }
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     *
     *  @return array
     */
    final public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    ACTION_CREATE,
                    ACTION_DELETE,
                    ACTION_INDEX,
                    ACTION_REMOVE,
                    ACTION_UPDATE,
                    ACTION_VIEW
                ],
                'rules' => [
                    [
                        ACTIONS => [
                            ACTION_CREATE,
                            ACTION_DELETE,
                            ACTION_INDEX,
                            ACTION_REMOVE,
                            ACTION_UPDATE,
                            ACTION_VIEW
                        ],
                        ALLOW => true,
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                ACTIONS => [
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX  => ['get'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW   => ['get'],
                ],
            ],
        ];
    }


    /**
     * Creates a new Controllers model. If creation is successful,
     * the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    final public function actionCreate()
    {
        $model = new Controllers();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->saveRecord($model);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }

    /**
     * Deletes an existing row of Controllers model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param int $id primary key table controllers
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception
     */
    final public function actionDelete($id)
    {
        $deleteRecord = new DeleteRecord();
        if (!$deleteRecord->isOkPermission(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);
        if ($model->fkCheck($model->controller_id)>0) {
            $deleteRecord->report(2);
            return $this->redirect([ACTION_INDEX]);
        }

        try {
            $common = new Common();
            $status = $common->transaction($model, ACTION_DELETE);
            $deleteRecord->report($status);
        } catch (\Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $exception,
                'actionDelete',
                MSG_ERROR
            );
        }

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Lists all Controllers models.
     *
     * @return mixed
     */
    final public function actionIndex()
    {

        $smControllers  = new ControllersSearch();
        $dpControllers = $smControllers->search(
            Yii::$app->request->queryParams
        );

        $pageSize = $this->pageSize();
        $dpControllers->pagination->pageSize=$pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'dataProviderControllers' => $dpControllers,
                PAGE_SIZE => $pageSize,
                'searchModelControllers' => $smControllers,
            ]
        );
    }

    /**
     * Delete many records of this table
     *
     * @return mixed
     */
    final public function actionRemove()
    {
        $result = Yii::$app->request->post('selection');
        $dControllers = new DeleteRecord();

        if ((!$dControllers->isOkPermission(ACTION_DELETE))
            || !$dControllers->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $dControllers->removeRecord(
            $result,
            Controllers::class,
            true
        );
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Controllers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int | string $controllerId primary key table controllers
     *
     * @return object Controllers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    final protected function findModel($controllerId)
    {
        $controllerId = $this->stringDecode($controllerId);
        if (($model = Controllers::findOne($controllerId)) !== null) {
            return $model;
        }

        $message = Yii::t(
            'app',
            'The requested page does not exist {id}',
            [ 'id' => $controllerId]
        );

        $bitacora = new Bitacora();
        $bitacora->register(
            $message,
            'findmodel',
            MSG_SECURITY_ISSUE
        );
        throw new NotFoundHttpException($message);
    }

    /**
     * Save or update information of Controllers
     *
     * @param object $model app\models\Controllers
     *
     * @return bool|\yii\web\Response
     */
    final private function saveRecord($model)
    {
        try {
            $common = new Common();
            $status = $common->transaction($model, 'save');
            $this->saveReport($status);
            if ($status) {
                $primaryKey = $this->stringEncode($model->controller_id);
                return $this->redirect([ACTION_VIEW, 'id' => $primaryKey]);
            }
        } catch (\yii\db\Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'saveRecord',
                MSG_ERROR
            );
        }
        return false;
    }

    /**
     * Updates an existing Controllers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table controllers
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    final public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())
            && $model->validate()
        ) {
            $this->saveRecord($model);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }

    /**
     * Displays a single Controllers model.
     *
     * @param integer $id Primary key of table controllers
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    final public function actionView($id)
    {
        $model = $this->findModel($id);
        $event = Yii::t('app', 'view record {id}', ['id' => $model->controller_id]);
        $bitacora = new Bitacora();
        $bitacora->register(
            $event,
            'actionView',
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
