<?php
/**
 * Actions
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
use app\models\Action;
use app\models\queries\Bitacora;
use app\models\search\ControllersSearch;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var object $searchModel app\models\search\ActionSearch */
/* @var object $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Action::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-list-alt',
    'is-white',
    $this->title,
    Yii::t(
        'app',
        'This view recollect all the views that exists in this web application.'
    ),
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
                [
                    STR_CLASS => GRID_DATACOLUMN,
                    ATTRIBUTE => Action::ACTION_ID,
                    OPTIONS => [STR_CLASS => 'col-sm-1'],
                    FORMAT => 'raw'
                ],
                [
                    STR_CLASS => GRID_DATACOLUMN,
                    ATTRIBUTE => Action::CONTROLLER_ID,
                    FILTER => ControllersSearch::getControllersListSearch(
                        Action::TABLE
                    ),
                    VALUE => Action::CONTROLLER_CONTROLLER_NAME,
                    FORMAT => 'raw',
                ],
                Action::ACTION_NAME,
                Action::ACTION_DESCRIPTION,
                [
                    STR_CLASS => GRID_DATACOLUMN,
                    FILTER => $uiComponent->yesOrNoArray(),
                    ATTRIBUTE => Action::ACTIVE,
                    OPTIONS => [STR_CLASS => COLSM1],
                    VALUE => function ($model) {
                        $uiComponent = new UiComponent();

                        return $uiComponent->yesOrNo($model->active);
                    },
                    FORMAT => 'raw'
                ],
            ]
        ]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\logs\actions::Gridview',
        MSG_ERROR
    );
}

$uiComponent->cardFooter('');
