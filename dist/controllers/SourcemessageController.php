<?php
/**
 * Source message
 * PHP Version 7.2.0
 *
 * @category  Controller
 * @package   Sourcemessage
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:03
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
use app\models\Sourcemessage;
use app\models\search\SourcemessageSearch;
use app\models\queries\Common;
use app\models\queries\Bitacora;

/**
 * Source message
 *
 * @category Controller
 * @package  Sourcemessage
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  Private license
 * @link     https://appwebd.github.io
 */
class SourcemessageController extends BaseController
{

    const ID = 'id';
    const MESSAGE = 'message';
    const SOURCE_MESSAGE_AUTOCOMPLETE = 'autocomplete';
    const SOURCE_MESSAGE_SEARCH_MODAL = 'searchmodal';
    const SOURCE_MESSAGE_CREATE_MODAL = 'createmodal';


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
                    self::SOURCE_MESSAGE_AUTOCOMPLETE,
                    self::SOURCE_MESSAGE_SEARCH_MODAL,
                    self::SOURCE_MESSAGE_CREATE_MODAL
                ],
                'rules' => [
                    [
                        ACTIONS => [
                            ACTION_DELETE,
                            ACTION_INDEX,
                            ACTION_REMOVE,
                            self::SOURCE_MESSAGE_AUTOCOMPLETE,
                            self::SOURCE_MESSAGE_SEARCH_MODAL,
                            self::SOURCE_MESSAGE_CREATE_MODAL
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
                    self::SOURCE_MESSAGE_AUTOCOMPLETE => ['get'],
                    self::SOURCE_MESSAGE_SEARCH_MODAL => ['get'],
                    self::SOURCE_MESSAGE_CREATE_MODAL => ['get']
                ],
            ],
        ];
    }

    /**
     * Deletes an existing row of Sourcemessage model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param integer $id Primary key of table source_message
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
        if ($model->fkCheck($model->id)>0) {
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
     * Lists all Sourcemessage models.
     *
     * @return mixed
     */
    final public function actionIndex()
    {

        $smSourcemessage = new SourcemessageSearch();
        $dpSourcemessage = $smSourcemessage->search(
            Yii::$app->request->queryParams
        );
        $pageSize = $this->pageSize();
        $dpSourcemessage->pagination->pageSize = $pageSize;

        $request = Yii::$app->request->post('Sourcemessage');
        if (isset($request, $request['primaryKey'])) {
            $id = $request['primaryKey'];
            $model = (empty($id)) ? new Sourcemessage() : $this->findModel($id);
            if ($model->load(Yii::$app->request->post())
            && $model->validate()
            && $model->save()
        ) {
                $msg = Yii::t('app', 'Record saved succefully');
                Yii::$app->session->setFlash('success', $msg);
            }
        } else {
            $model = new Sourcemessage();
        }

        return $this->render(
            ACTION_INDEX,
            [
                'dataProviderSourcemessage' => $dpSourcemessage,
                MODEL => $model,
                PAGE_SIZE => $pageSize,
                'searchModelSourcemessage' => $smSourcemessage,
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
        $dsource_message = new DeleteRecord();

        if ((!$dsource_message->isOkPermission(ACTION_DELETE))
            || !$dsource_message->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $dsource_message->removeRecord(
            $result,
            Sourcemessage::class,
            true
        );

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Sourcemessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int | string $id Primary key table source_message
     *
     * @return object Sourcemessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    final protected function findModel($id)
    {
        $id = $this->stringDecode($id);
        if (($model = Sourcemessage::findOne($id)) !== null) {
            return $model;
        }

        $message = Yii::t(
            'app',
            'The requested page does not exist {id}',
            [ 'id' => $id]
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
    * Search Source message
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
                $model = Source_message::findOne(
                    [self::ID => $term]
                );

                if ($model) {
                    $results[] = [
                        self::ID => $model[self::ID],
                        LABEL => $model[self::MESSAGE]
                                . '|' .
                                $model[self::ID]
                    ];
                }
            } else {
                $query = addslashes($term);
                $where = "(`" . self::MESSAGE ."` like '%{$query}%')";
                $aModels = Source_message::find()
                            ->where($where)
                            ->all();
                foreach ($aModels as $model) {
                    $results[] = [
                        self::ID => $model[self::ID],
                        LABEL => $model[self::MESSAGE] .
                            '|' .
                            $model[self::ID],
                    ];
                }
            }
            echo Json::encode($results);
            Yii::$app->end();
        }
    }

    /**
    * Search modal view of Source_message
    *
    * @param integer id integer primary key of table source_message
    *
    * @return void
    */
    final public function actionSearchmodal()
    {

        $searchModel = new Source_messageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;
        $dataProvider->query->andWhere('source_message.active = 1');

        return $this->renderAjax(
            '_SourcemessageSearch_modal',
            [
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
                SEARCH_MODEL => $searchModel,
            ]
        );
    }

    /**
     * Creates a new Source_message model. If creation is successful,
     * the browser will be redirected to the \'view\' called of
     * this method.
     *
     * @return mixed
     */
    final public function actionCreatemodal()
    {
        $model = new Source_message();

        if ($model->load(Yii::$app->request->post()) && Common::transaction($model, 'save')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $result=[];
            $result[] = [
                'code' => $model->id,
                'description' => $model->message
            ];
            echo Json::encode($result);
            Yii::$app->end();
        }

        return $this->renderAjax(
            '_SourcemessageCreate_modal',
            [MODEL=> $model]
        );
    }
}
