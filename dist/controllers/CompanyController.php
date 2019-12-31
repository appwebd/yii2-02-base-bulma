<?php
/**
 * Company
 * PHP Version 7.2.0
 *
 * @category  Controllers
 * @package   Company
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
use app\models\Company;
use app\models\search\CompanySearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Company
 *
 * @category Controller
 * @package  Company
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  Private license
 * @link     https://appwebd.github.io
 */
class CompanyController extends BaseController
{
    const COMPANY_ID = 'company_id';


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
     * Creates a new Company model. If creation is successful,
     * the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    final public function actionCreate()
    {
        $model = new Company();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->saveRecord($model);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }

    /**
     * Deletes an existing row of Company model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param int $id primary key table company
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
        if ($model->fkCheck($model->company_id)>0) {
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
     * Lists all Company models.
     *
     * @return mixed
     */
    final public function actionIndex()
    {

        $smCompany  = new CompanySearch();
        $dpCompany = $smCompany->search(
            Yii::$app->request->queryParams
        );

        $pageSize = $this->pageSize();
        $dpCompany->pagination->pageSize=$pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'dataProviderCompany' => $dpCompany,
                PAGE_SIZE => $pageSize,
                'searchModelCompany' => $smCompany,
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
        $dCompany = new DeleteRecord();

        if ((!$dCompany->isOkPermission(ACTION_DELETE))
            || !$dCompany->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $dCompany->removeRecord(
            $result,
            Company::class,
            true
        );
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int | string $companyId primary key table company
     *
     * @return object Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    final protected function findModel($companyId)
    {
        $companyId = $this->stringDecode($companyId);
        if (($model = Company::findOne($companyId)) !== null) {
            return $model;
        }

        $message = Yii::t(
            'app',
            'The requested page does not exist {id}',
            [ 'id' => $companyId]
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
     * Save or update information of Company
     *
     * @param object $model app\models\Company
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
                $primaryKey = $this->stringEncode($model->company_id);
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
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table company
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
     * Displays a single Company model.
     *
     * @param integer $id Primary key of table company
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    final public function actionView($id)
    {
        $model = $this->findModel($id);
        $event = Yii::t('app', 'view record {id}', ['id' => $model->company_id]);
        $bitacora = new Bitacora();
        $bitacora->register(
            $event,
            'actionView',
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
