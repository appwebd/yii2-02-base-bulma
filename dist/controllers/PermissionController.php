<?php
/**
 * Permission
 * PHP version 7.0
 *
 * @category  Controller
 * @package   Permission
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2018-08-09 14:26:44
 */

namespace app\controllers;

use app\components\DeleteRecord;
use app\models\Action;
use app\models\Permission;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\search\PermissionSearch;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PermissionController extends BaseController
{
    const ACTION_DROPDOWN = 'actiondropdown';
    const CONTROLLER_ID = 'controller_id';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action action
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
            'beforeAction',
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
        return [
            'access' => [
                STR_CLASS => AccessControl::class,
                'only' => [
                    self::ACTION_DROPDOWN,
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
                            self::ACTION_DROPDOWN,
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
                STR_CLASS => VerbFilter::class,
                ACTIONS => [
                    self::ACTION_DROPDOWN => ['get'],
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX => ['get', 'post'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW => ['get'],
                ],
            ],
        ];
    }

    /**
     * Creates a new Permission model. If creation is successful,
     * the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Permission();

        if ($model->load(Yii::$app->request->post())) {
            $this->_saveRecord($model, 'permission_id');
        }

        return $this->render(ACTION_CREATE, [MODEL => $model, 'titleView' => 'Create']);
    }

    /**
     * Save record
     *
     * @param object $model    app\models\Permission
     * @param string $columnPk Primary Key column name
     *
     * @return    bool|Response
     * @Exception
     */
    private function _saveRecord($model, $columnPk)
    {
        try {
            $common = new Common();
            $status = $common->transaction($model, 'save');
            $this->saveReport($status);
            if ($status) {
                $primaryKey = BaseController::stringDecode($model->$columnPk);
                return $this->redirect([ACTION_VIEW, 'id' => $primaryKey]);
            }
        } catch (Exception $exception) {
            $event = Yii::t(
                'app',
                'Error saving record: {error}',
                ['error' => $exception]
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $event,
                'saveRecord',
                MSG_ERROR
            );
        }
        return false;
    }

    /**
     * Deletes an existing row of Permission model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param integer $id Primary key of table Permission
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

        try {
            $common = new Common();
            $status = $common->transaction($model, ACTION_DELETE);
            $deleteRecord->report($status);
        } catch (Exception $exception) {
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
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int|string $permissionId primary key of table permission
     *
     * @return object Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($permissionId)
    {
        $permissionId = self::stringDecode($permissionId);
        if (($model = Permission::findOne($permissionId)) !== null) {
            return $model;
        }
        $event = Yii::t(
            'app',
            'The requested page does not exist {id}',
            [
                'id' => $permissionId
            ]
        );

        $bitacora = new Bitacora();
        $bitacora->register($event, '', MSG_ERROR);
        throw new NotFoundHttpException(
            Yii::t(
                'app',
                'The requested page does not exist.'
            )
        );
    }

    /**
     * Select object, loaded via ajax method
     *
     * @param integer $id primary key of table relations
     *
     * @return void
     */
    public function actionActiondropdown($id)
    {
        if (Yii::$app->request->isAjax) {
            echo Common::relatedDropdownList(
                Action::class,
                self::CONTROLLER_ID,
                $id,
                'action_id',
                'action_name',
                'action_name'
            );
        }
    }

    /**
     * Lists all Permission models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $sm_permission = new PermissionSearch();
        $dp_permission = $sm_permission->search(
            Yii::$app->request->queryParams
        );

        $pageSize = $this->pageSize();
        $dp_permission->pagination->pageSize = $pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'dataProvider' => $dp_permission,
                PAGE_SIZE => $pageSize,
                'searchModel' => $sm_permission,
                'controller_id' => $sm_permission->controller_id
            ]
        );
    }

    /**
     * Delete many records of this table
     *
     * @return mixed
     */
    public function actionRemove()
    {
        $delete_record = new DeleteRecord();
        $result = Yii::$app->request->post('selection');

        if (!$delete_record->isOkPermission(ACTION_DELETE)
            || !$delete_record->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $nro_selections = sizeof($result);
        $status = [];
        for ($counter = 0; $counter < $nro_selections; $counter++) {
            try {
                $primary_key = $result[$counter];
                $model = Permission::findOne($primary_key);
                $item = $delete_record->remove($model, 0);
                $status[$item] .= $primary_key . ',';
            } catch (Exception $exception) {
                $bitacora = new Bitacora();
                $bitacora->registerAndFlash(
                    $exception,
                    'actionRemove',
                    MSG_ERROR
                );
            }
        }

        $delete_record->summaryDisplay($status);
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table permission
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $this->_saveRecord($model, 'permission_id');
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
     * Displays a single Permission model.
     *
     * @param integer $id Primary key of table Permission
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $event = Yii::t('app', 'view record {id}', ['id' => $model->permission_id]);
        $bitacora = new Bitacora();
        $bitacora->register($event, 'actionView', MSG_INFO);

        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
