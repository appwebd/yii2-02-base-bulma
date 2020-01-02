<?php
/**
 * Logs User bitacora
 * PHP version 7.2.0
 *
 * @category  View
 * @package   Logs
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

use app\components\UiComponent;
use app\models\Logs;
use app\models\queries\Bitacora;
use app\models\search\LogsSearch;
use app\models\Status;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $controller_id int app\controllers\LogsController */
/* @var $dataProvider int app\controllers\LogsController */
/* @var $searchModel int app\controllers\LogsController */
/* @var $pageSize int app\controllers\LogsController */
/* @var $searchModel app\models\search\LogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Logs::TITLE);
$this->params[BREADCRUMBS][] = $this->title;
$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    Logs::ICON,
    'is-white',
    $this->title,
    Yii::t('app', 'Event log of the web application.'),
    '000',
    true
);

try {
    echo GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => GRIDVIEW_LAYOUT,
            'filterSelector' => 'select[name="per-page"]',
            'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
            'columns' => [
                Logs::LOGS_ID,
                Logs::DATE,

                [
                    ATTRIBUTE => Logs::STATUS_ID,
                    FILTER => LogsSearch::getStatusListSearch(),
                    FORMAT => 'raw',
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => function ($model) {
                        $status = Status::getStatusName($model->status_id);
                        $uiComponent = new UiComponent();
                        return $uiComponent->badgetStatus($model->status_id, $status);
                    },
                ],
                [
                    ATTRIBUTE => Logs::CONTROLLER_ID,
                    FILTER => LogsSearch::getControllersListSearch(),
                    FORMAT => 'raw',
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => Logs::CONTROLLER_CONTROLLER_NAME,
                ],
                [
                    ATTRIBUTE => Logs::ACTION_ID,
                    FILTER => LogsSearch::getActionListSearch($controller_id),
                    FORMAT => 'raw',
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => Logs::ACTION_ACTION_NAME,
                ],
                Logs::EVENT,
                [
                    ATTRIBUTE => Logs::USER_ID,
                    FILTER => LogsSearch::getUserList(),
                    FORMAT => 'raw',
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => 'user.username'
                ],

            ]
        ]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->registerAndFlash(
        $exception,
        'app\views\logs\index::Gridview',
        MSG_ERROR
    );
}

$uiComponent->cardFooter('');
