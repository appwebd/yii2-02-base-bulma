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
use app\models\queries\Bitacora;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ControllersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Controllers::TITLE);
$this->params[BREADCRUMBS][] = $this->title;
$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    Controllers::ICON,
    'is-white',
    $this->title,
    Yii::t('app', 'This view recollect all the controllers that exists in this web application'),
    '000',
    true
);

try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' =>GRIDVIEW_LAYOUT,
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            [
                ATTRIBUTE => Controllers::CONTROLLER_ID,
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => COLSM1],
                STR_CLASS => yii\grid\DataColumn::className(),
            ],
            Controllers::CONTROLLER_NAME,
            Controllers::CONTROLLER_DESCRIPTION,
            [
                ATTRIBUTE => Controllers::MENU_BOOLEAN_PRIVATE,
                FILTER => $uiComponent->yesOrNoArray(),
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => 'col-sm-1'],
                STR_CLASS => yii\grid\DataColumn::className(),
                VALUE => function ($model) {
                    $uiComponent =  new UiComponent();
                    return $uiComponent->yesOrNo($model->active);
                },
            ],
            [
                ATTRIBUTE => Controllers::MENU_BOOLEAN_VISIBLE,
                FILTER => $uiComponent->yesOrNoArray(),
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => COLSM1],
                STR_CLASS => yii\grid\DataColumn::className(),
                VALUE => function ($model) {
                    $uiComponent = new UiComponent();
                    return $uiComponent->yesOrNo($model->active);
                },
            ],
            [
                ATTRIBUTE => Controllers::ACTIVE,
                FILTER => $uiComponent->yesOrNoArray(),
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => COLSM1],
                STR_CLASS => yii\grid\DataColumn::className(),
                VALUE => function ($model) {
                    $uiComponent = new UiComponent();
                    return $uiComponent->yesOrNo($model->active);
                },
            ],
        ]
    ]);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->registerAndFlash(
        $exception,
        'app/views/logs/Controllers',
        MSG_ERROR
    );
}

$uiComponent->cardFooter('');
