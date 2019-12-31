<?php
/**
 * Informative status of events in all the platform
 * PHP Version 7.2.0
 *
 * @category  Controllers
 * @package   Status
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
use app\models\Status;
use app\models\search\StatusSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Informative status of events in all the platform
 *
 * @category Controller
 * @package  Status
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  Private license
 * @link     https://appwebd.github.io
 */
class StatusController extends BaseController
{
    const STATUS_ID = 'status_id';


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
     * Creates a new Status model. If creation is successful,
     * the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    final public function actionCreate()
    {
        $model = new Status();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->saveRecord($model);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }

    /**
     * Deletes an existing row of Status model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param int $id primary key table status
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
        if ($model->fkCheck($model->status_id)>0) {
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
     * Lists all Status models.
     *
     * @return mixed
     */
    final public function actionIndex()
    {

        $smStatus  = new StatusSearch();
        $dpStatus = $smStatus->search(
            Yii::$app->request->queryParams
        );

        $pageSize = $this->pageSize();
        $dpStatus->pagination->pageSize=$pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'dataProviderStatus' => $dpStatus,
                PAGE_SIZE => $pageSize,
                'searchModelStatus' => $smStatus,
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
        $dStatus = new DeleteRecord();

        if ((!$dStatus->isOkPermission(ACTION_DELETE))
            || !$dStatus->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $dStatus->removeRecord(
            $result,
            Status::class,
            true
        );
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Status model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int | string $statusId primary key table status
     *
     * @return object Status the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    final protected function findModel($statusId)
    {
        $statusId = $this->stringDecode($statusId);
        if (($model = Status::findOne($statusId)) !== null) {
            return $model;
        }

        $message = Yii::t(
            'app',
            'The requested page does not exist {id}',
            [ 'id' => $statusId]
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
     * Save or update information of Status
     *
     * @param object $model app\models\Status
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
                $primaryKey = $this->stringEncode($model->status_id);
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
     * Updates an existing Status model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table status
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
     * Displays a single Status model.
     *
     * @param integer $id Primary key of table status
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    final public function actionView($id)
    {
        $model = $this->findModel($id);
        $event = Yii::t('app', 'view record {id}', ['id' => $model->status_id]);
        $bitacora = new Bitacora();
        $bitacora->register(
            $event,
            'actionView',
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
