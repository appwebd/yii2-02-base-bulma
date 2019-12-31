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
/* @var $searchModel app\models\search\ActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Action::TITLE);
$this->params[BREADCRUMBS][] = $this->title;


$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-list-alt fa-2x',
    'is-white',
    $this->title,
    Yii::t(
        'app',
        'This view recollect all the views in this web application.
        (for  assigns of privileges and access control)'
    ),
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
                    ATTRIBUTE => Action::ACTION_ID,
                    OPTIONS => [STR_CLASS => COLSM1],
                    FORMAT => 'raw',
                    STR_CLASS => GRID_DATACOLUMN,
                ],
                [
                    ATTRIBUTE => Action::CONTROLLER_ID,
                    FILTER => ControllersSearch::getControllersListSearch(
                        Action::TABLE
                    ),
                    FORMAT => 'raw',
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => Action::CONTROLLER_CONTROLLER_NAME,
                ],
                Action::ACTION_NAME,
                Action::ACTION_DESCRIPTION,
                [
                    FILTER => UiComponent::yesOrNoArray(),
                    ATTRIBUTE => Action::ACTIVE,
                    OPTIONS => [STR_CLASS => COLSM1],
                    VALUE => function ($model) {
                        return UiComponent::yesOrNo($model->active);
                    },
                    FORMAT => 'raw',
                    STR_CLASS => GRID_DATACOLUMN,
                ],
            ]
        ]
    );
} catch (Exception $errorException) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'views\logs\action::Gridview',
        MSG_ERROR
    );
}

$uiComponent->cardFooter('');
