<?php
/**
 * Controllers
 *
 * @package     Index of Controllers
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 19:20:11
 * @version     1.0
 */

use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\Controllers;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ControllersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Controllers::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'eye-open',
    'is-white',
    $this->title,
    Yii::t(
        'app',
        'This view recollect all the controllers that exists in this web application'
    ),
    '000',
    true
);

try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}
                     <div class="columns">
                     <div class="column">
                     <br>
                     {summary}
                     </div>
                     <div class="column">{pager}</div>',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            [
                ATTRIBUTE => Controllers::CONTROLLER_ID,
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => COLSM1],
                STR_CLASS => GRID_DATACOLUMN,
            ],
            Controllers::CONTROLLER_NAME,
            Controllers::CONTROLLER_DESCRIPTION,
            [
                ATTRIBUTE => Controllers::MENU_BOOLEAN_PRIVATE,
                FILTER => UiComponent::yesOrNoArray(),
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => 'col-sm-2'],
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
            ],
            [
                ATTRIBUTE => Controllers::MENU_BOOLEAN_VISIBLE,
                FILTER => UiComponent::yesOrNoArray(),
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => COLSM1],
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
            ],
            [
                ATTRIBUTE => Controllers::ACTIVE,
                FILTER => UiComponent::yesOrNoArray(),
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => COLSM1],
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
            ],
        ]
    ]);
} catch (Exception $exception) {
    BaseController::bitacora(
        Yii::t(
            'app',
            'Failed to show information, error: {error}',
            ['error' => $exception]
        ),
        MSG_ERROR
    );
}

UiComponent::cardFooter('');
