<?php
/**
 * Actions
 * PHP Version 7.2.0
 *
 * @category  Controller
 * @package   Action
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:03
 */

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\base\ErrorHandler;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\DeleteRecord;
use app\components\UiComponent;
use app\models\Action;
use app\models\search\ActionSearch;
use app\models\queries\Common;
use app\models\queries\Bitacora;

/**
 * Actions
 *
 * @category Controller
 * @package  Action
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  Private license
 * @link     https://appwebd.github.io
 */
class ActionController extends BaseController
{

    const ACTION_ID = 'action_id';
    const ACTION_NAME = 'action_name';
    const ACTION_AUTOCOMPLETE = 'autocomplete';
    const ACTION_SEARCH_MODAL = 'searchmodal';
    const ACTION_CREATE_MODAL = 'createmodal';


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
     * @return array
     */
    final public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    ACTION_DELETE,
                    ACTION_INDEX,
                    ACTION_REMOVE,
                    self::ACTION_AUTOCOMPLETE,
                    self::ACTION_SEARCH_MODAL,
                    self::ACTION_CREATE_MODAL
                ],
                'rules' => [
                    [
                        ACTIONS => [
                            ACTION_DELETE,
                            ACTION_INDEX,
                            ACTION_REMOVE,
                            self::ACTION_AUTOCOMPLETE,
                            self::ACTION_SEARCH_MODAL,
                            self::ACTION_CREATE_MODAL
                        ],
                        ALLOW => true,
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                ACTIONS => [
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX  => ['get', 'post'],
                    ACTION_REMOVE => ['post'],
                    self::ACTION_AUTOCOMPLETE => ['get'],
                    self::ACTION_SEARCH_MODAL => ['get'],
                    self::ACTION_CREATE_MODAL => ['get']
                ],
            ],
        ];
    }

    /**
     * Deletes an existing row of Action model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param integer $id Primary key of table action
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    final public function actionDelete($id)
    {

        $deleteRecord = new DeleteRecord();
        if (!$deleteRecord->isOkPermission(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);
        if ($model->fkCheck($model->action_id)>0) {
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
     * Lists all Action models.
     *
     * @return mixed
     */
    final public function actionIndex()
    {

        $smAction = new ActionSearch();
        $dpAction = $smAction->search(
            Yii::$app->request->queryParams
        );
        $pageSize = $this->pageSize();
        $dpAction->pagination->pageSize = $pageSize;

        $request = Yii::$app->request->post('Action');
        if (isset($request, $request['primaryKey'])) {
            $actionId = $request['primaryKey'];
            $model = (empty($actionId)) ? new Action() : $this->findModel($actionId);
            if ($model->load(Yii::$app->request->post())
            && $model->validate()
            && $model->save()
        ) {
                $msg = Yii::t('app', 'Record saved succefully');
                Yii::$app->session->setFlash('success', $msg);
            }
        } else {
            $model = new Action();
        }

        return $this->render(
            ACTION_INDEX,
            [
                'dataProviderAction' => $dpAction,
                MODEL => $model,
                PAGE_SIZE => $pageSize,
                'searchModelAction' => $smAction,
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
        $daction = new DeleteRecord();

        if ((!$daction->isOkPermission(ACTION_DELETE))
            || !$daction->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $daction->removeRecord(
            $result,
            Action::class,
            true
        );

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Action model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int | string $actionId Primary key table action
     *
     * @return object Action the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    final protected function findModel($actionId)
    {
        $actionId = $this->stringDecode($actionId);
        if (($model = Action::findOne($actionId)) !== null) {
            return $model;
        }

        $message = Yii::t(
            'app',
            'The requested page does not exist {id}',
            [ 'id' => $actionId]
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
    * Search Actions
    *
    * @param string $term pattern to search
    *
    * @return void
    */
    final public function actionAutocomplete($term)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $results = [];
            if (is_numeric($term)) {
                $model = Action::findOne(
                    [self::ACTION_ID => $term]
                );

                if ($model) {
                    $results[] = [
                        self::ACTION_ID => $model[self::ACTION_ID],
                        LABEL => $model[self::ACTION_NAME]
                                . '|' .
                                $model[self::ACTION_ID]
                    ];
                }
            } else {
                $query = addslashes($term);
                $where = "(`" . self::ACTION_NAME ."` like '%{$query}%')";
                $aModels = Action::find()
                            ->where($where)
                            ->all();
                foreach ($aModels as $model) {
                    $results[] = [
                        self::ACTION_ID => $model[self::ACTION_ID],
                        LABEL => $model[self::ACTION_NAME] .
                            '|' .
                            $model[self::ACTION_ID],
                    ];
                }
            }
            echo Json::encode($results);
            Yii::$app->end();
        }
    }

    /**
    * Search modal view of Action
    *
    * @param integer action_id integer primary key of table action
    *
    * @return void
    */
    final public function actionSearchmodal()
    {

        $searchModel = new ActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;
        $dataProvider->query->andWhere('action.active = 1');

        return $this->renderAjax(
            '_ActionSearch_modal',
            [
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
                SEARCH_MODEL => $searchModel,
            ]
        );
    }

    /**
     * Creates a new Action model. If creation is successful,
     * the browser will be redirected to the \'view\' called of
     * this method.
     *
     * @return mixed
     */
    final public function actionCreatemodal()
    {
        $model = new Action();

        if ($model->load(Yii::$app->request->post()) && Common::transaction($model, 'save')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $result=[];
            $result[] = [
                'code' => $model->action_id,
                'description' => $model->action_name
            ];
            echo Json::encode($result);
            Yii::$app->end();
        }

        return $this->renderAjax(
            '_ActionCreate_modal',
            [MODEL=> $model]
        );
    }
}
