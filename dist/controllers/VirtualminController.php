<?php
/**
 * virtualmin
 * PHP Version 7.2.0
 *
 * @category  Controllers
 * @package   Virtualmin
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @release   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:03
 */

namespace app\controllers;

use app\components\DeleteRecord;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\Virtualmin;
use app\models\search\VirtualminSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * virtualmin
 *
 * @category Controller
 * @package  Virtualmin
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  Private license
 * @link     https://appwebd.github.io
 */
class VirtualminController extends BaseController
{
    const VIRTUALMIN_ID = 'virtualmin_id';


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
     * Creates a new Virtualmin model. If creation is successful,
     * the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    final public function actionCreate()
    {
        $model = new Virtualmin();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->saveRecord($model);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }

    /**
     * Deletes an existing row of Virtualmin model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param int $id primary key table virtualmin
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
        if ($model->fkCheck($model->virtualmin_id)>0) {
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
     * Lists all Virtualmin models.
     *
     * @return mixed
     */
    final public function actionIndex()
    {

        $smVirtualmin  = new VirtualminSearch();
        $dpVirtualmin = $smVirtualmin->search(
            Yii::$app->request->queryParams
        );

        $pageSize = $this->pageSize();
        $dpVirtualmin->pagination->pageSize=$pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'dataProviderVirtualmin' => $dpVirtualmin,
                PAGE_SIZE => $pageSize,
                'searchModelVirtualmin' => $smVirtualmin,
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
        $dVirtualmin = new DeleteRecord();

        if ((!$dVirtualmin->isOkPermission(ACTION_DELETE))
            || !$dVirtualmin->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $dVirtualmin->removeRecord(
            $result,
            Virtualmin::class,
            true
        );
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Virtualmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int | string $virtualminId primary key table virtualmin
     *
     * @return object Virtualmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    final protected function findModel($virtualminId)
    {
        $virtualminId = $this->stringDecode($virtualminId);
        if (($model = Virtualmin::findOne($virtualminId)) !== null) {
            return $model;
        }

        $message = Yii::t(
            'app',
            'The requested page does not exist {id}',
            [ 'id' => $virtualminId]
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
     * Save or update information of Virtualmin
     *
     * @param object $model app\models\Virtualmin
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
                $primaryKey = $this->stringEncode($model->virtualmin_id);
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
     * Updates an existing Virtualmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table virtualmin
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
     * Displays a single Virtualmin model.
     *
     * @param integer $id Primary key of table virtualmin
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    final public function actionView($id)
    {
        $model = $this->findModel($id);
        $event = Yii::t('app', 'view record {id}', ['id' => $model->virtualmin_id]);
        $bitacora = new Bitacora();
        $bitacora->register(
            $event,
            'actionView',
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
