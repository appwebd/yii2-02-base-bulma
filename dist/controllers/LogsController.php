<?php
/**
 * Logs (user bitacora)
 *
 * @category  Controllers
 * @package   Logs
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   BSD 3-clause Clear license
 * @link      https://appwebd.github.io
 * @date      2018-07-30 15:34:07
 * @version   SVN: $Id$
 * @php       version 7.2
 */

namespace app\controllers;

use app\models\queries\Bitacora;
use app\models\search\ActionSearch;
use app\models\search\BlockedSearch;
use app\models\search\ControllersSearch;
use app\models\search\LogsSearch;
use app\models\search\StatusSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * LogsController class permit logs in bitacora table
 */
class LogsController extends BaseController
{
    const ACTIONS = 'actions';
    const BLOCKED = 'blocked';
    const CONTROLLER_ID = 'controller_id';
    const CONTROLLERS = 'controllers';
    const STATUS = 'status';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action name of object invoked.
     * @return mixed
     * @throws object \yii\web\BadRequestHttpException
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
                    self::ACTIONS,
                    self::BLOCKED,
                    self::CONTROLLERS,
                    ACTION_INDEX,
                    self::STATUS,
                ],
                'rules' => [
                    [
                        ACTIONS => [
                            self::ACTIONS,
                            self::BLOCKED,
                            self::CONTROLLERS,
                            ACTION_INDEX,
                            self::STATUS,
                        ],
                        ALLOW => true,
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                STR_CLASS => VerbFilter::class,
                ACTIONS => [
                    self::ACTIONS => ['get'],
                    self::BLOCKED => ['get'],
                    self::CONTROLLERS => ['get'],
                    ACTION_INDEX => ['get'],
                    self::STATUS => ['get'],
                ],
            ],
        ];
    }

    /**
     * Lists all Action models.
     *
     * @return mixed
     */
    public function actionActions()
    {

        $actionsSearchModel = new ActionSearch();
        $dataProvider = $actionsSearchModel->search(Yii::$app->request->queryParams);
        $pageSize = $this->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render(
            self::ACTIONS,
            [
                SEARCH_MODEL => $actionsSearchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize
            ]
        );
    }

    /**
     * Lists all Blocked models.
     *
     * @return mixed
     */
    public function actionBlocked()
    {

        $searchModel = new BlockedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pageSize = $this->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render(
            self::BLOCKED,
            [
                SEARCH_MODEL => $searchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize
            ]
        );
    }

    /**
     * Lists all Controllers models.
     *
     * @return mixed
     */
    public function actionControllers()
    {
        $searchModel = new ControllersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pageSize = $this->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render(
            self::CONTROLLERS,
            [
                SEARCH_MODEL => $searchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
            ]
        );
    }

    /**
     * Lists all Logs models.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $logsSearchModel = new LogsSearch();
        $dataProvider = $logsSearchModel->search(Yii::$app->request->queryParams);

        $pageSize = $this->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;
        $request = Yii::$app->request->get('LogsSearch');
        if (isset($request[self::CONTROLLER_ID])) {
            $controllerId = $request[self::CONTROLLER_ID];
        } else {
            $controllerId = null;
        }

        return $this->render(
            ACTION_INDEX,
            [
                SEARCH_MODEL => $logsSearchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
                self::CONTROLLER_ID => $controllerId
            ]
        );
    }

    /**
     * Lists all Status models.
     *
     * @return mixed
     */
    public function actionStatus()
    {
        $searchModel = new StatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pageSize = $this->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render(
            self::STATUS,
            [
                SEARCH_MODEL => $searchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
            ]
        );
    }
}
