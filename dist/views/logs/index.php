<?php
/**
 * Logs
 * PHP Version 7.0.0
 *
 * @category  View
 * @package   Logs
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 10:07 PM
 */

use app\components\UiComponent;
use app\components\UiButtons;
use app\models\Logs;
use app\models\search\LogsSearch;
use app\models\Status;
use yii\grid\GridView;
use app\models\queries\Bitacora;

/* @var $this yii\web\View */
/* @var int $controller_id app\controllers\LogsController */
/* @var object $dataProvider app\controllers\LogsController */
/* @var object $searchModel app\controllers\LogsController */
/* @var int $pageSize app\controllers\LogsController */

$this->title = Yii::t('app', Logs::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-sign-out-alt fa-2x',
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
        'layout' => '{items}
                     <div class="columns">
                        <div class="column gridSummary">
                            {summary}
                        </div>
                        <div class="column">
                            {pager}
                        </div>',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            [
                ATTRIBUTE => Logs::LOGS_ID,
                FORMAT => 'raw',
                CONTENT_OPTIONS=>[STR_CLASS=>' is-1 '],
                STR_CLASS => GRID_DATACOLUMN,
            ],

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
        ]]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->registerAndFlash(
        $exception,
        'app\views\logs\index::gridView::widget',
        MSG_ERROR
    );
}
$uiComponent->cardFooter('');
