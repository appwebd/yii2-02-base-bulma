<?php
/**
 * Translation of messages
 * PHP Version 7.2.0
 *
 * @category  Controller
 * @package   Message
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
use app\models\Message;
use app\models\search\MessageSearch;
use app\models\queries\Common;
use app\models\queries\Bitacora;

/**
 * Translation of messages
 *
 * @category Controller
 * @package  Message
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  Private license
 * @link     https://appwebd.github.io
 */
class MessageController extends BaseController
{

    const ID = 'id';
    const TRANSLATION = 'translation';
    const MESSAGE_AUTOCOMPLETE = 'autocomplete';
    const MESSAGE_SEARCH_MODAL = 'searchmodal';
    const MESSAGE_CREATE_MODAL = 'createmodal';


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
                    self::MESSAGE_AUTOCOMPLETE,
                    self::MESSAGE_SEARCH_MODAL,
                    self::MESSAGE_CREATE_MODAL
                ],
                'rules' => [
                    [
                        ACTIONS => [
                            ACTION_DELETE,
                            ACTION_INDEX,
                            ACTION_REMOVE,
                            self::MESSAGE_AUTOCOMPLETE,
                            self::MESSAGE_SEARCH_MODAL,
                            self::MESSAGE_CREATE_MODAL
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
                    self::MESSAGE_AUTOCOMPLETE => ['get'],
                    self::MESSAGE_SEARCH_MODAL => ['get'],
                    self::MESSAGE_CREATE_MODAL => ['get']
                ],
            ],
        ];
    }

    /**
     * Deletes an existing row of Message model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param integer $id Primary key of table message
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
     * Lists all Message models.
     *
     * @return mixed
     */
    final public function actionIndex()
    {

        $smMessage = new MessageSearch();
        $dpMessage = $smMessage->search(
            Yii::$app->request->queryParams
        );
        $pageSize = $this->pageSize();
        $dpMessage->pagination->pageSize = $pageSize;

        $request = Yii::$app->request->post('Message');
        if (isset($request, $request['primaryKey'])) {
            $id = $request['primaryKey'];
            $model = (empty($id)) ? new Message() : $this->findModel($id);
            if ($model->load(Yii::$app->request->post())
            && $model->validate()
            && $model->save()
        ) {
                $msg = Yii::t('app', 'Record saved succefully');
                Yii::$app->session->setFlash('success', $msg);
            }
        } else {
            $model = new Message();
        }

        return $this->render(
            ACTION_INDEX,
            [
                'dataProviderMessage' => $dpMessage,
                MODEL => $model,
                PAGE_SIZE => $pageSize,
                'searchModelMessage' => $smMessage,
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
        $dmessage = new DeleteRecord();

        if ((!$dmessage->isOkPermission(ACTION_DELETE))
            || !$dmessage->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $dmessage->removeRecord(
            $result,
            Message::class,
            true
        );

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int | string $id Primary key table message
     *
     * @return object Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    final protected function findModel($id)
    {
        $id = $this->stringDecode($id);
        if (($model = Message::findOne($id)) !== null) {
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
    * Search Translation of messages
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
                $model = Message::findOne(
                    [self::ID => $term]
                );

                if ($model) {
                    $results[] = [
                        self::ID => $model[self::ID],
                        LABEL => $model[self::TRANSLATION]
                                . '|' .
                                $model[self::ID]
                    ];
                }
            } else {
                $query = addslashes($term);
                $where = "(`" . self::TRANSLATION ."` like '%{$query}%')";
                $aModels = Message::find()
                            ->where($where)
                            ->all();
                foreach ($aModels as $model) {
                    $results[] = [
                        self::ID => $model[self::ID],
                        LABEL => $model[self::TRANSLATION] .
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
    * Search modal view of Message
    *
    * @param integer id integer primary key of table message
    *
    * @return void
    */
    final public function actionSearchmodal()
    {

        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;
        $dataProvider->query->andWhere('message.active = 1');

        return $this->renderAjax(
            '_MessageSearch_modal',
            [
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
                SEARCH_MODEL => $searchModel,
            ]
        );
    }

    /**
     * Creates a new Message model. If creation is successful,
     * the browser will be redirected to the \'view\' called of
     * this method.
     *
     * @return mixed
     */
    final public function actionCreatemodal()
    {
        $model = new Message();

        if ($model->load(Yii::$app->request->post()) && Common::transaction($model, 'save')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $result=[];
            $result[] = [
                'code' => $model->id,
                'description' => $model->translation
            ];
            echo Json::encode($result);
            Yii::$app->end();
        }

        return $this->renderAjax(
            '_MessageCreate_modal',
            [MODEL=> $model]
        );
    }
}
