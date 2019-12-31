<?php
/**
 * Informative status of events in all the platform
 *
 * @package     Index of Status
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 19:28:34
 * @version     1.0
 */

use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\Status;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Status::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'road',
    'is-white',
    $this->title,
    Yii::t('app', 'This view exists for to do more easy the stadistica process in the web application'),
    '000',
    true
);

try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{summary}{pager}',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            [
                STR_CLASS => yii\grid\DataColumn::className(),
                ATTRIBUTE => Status::STATUS_ID,
                OPTIONS => [STR_CLASS => COLSM1],
                FORMAT => 'raw'
            ],
            Status::STATUS_NAME,
            [
                STR_CLASS => yii\grid\DataColumn::className(),
                FILTER => UiComponent::yesOrNoArray(),
                ATTRIBUTE => Status::ACTIVE,
                OPTIONS => [STR_CLASS => COLSM1],
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
                FORMAT => 'raw'
            ],

        ]
    ]);
} catch (Exception $errorexception) {
    BaseController::bitacora(
        Yii::t(
            'app',
            'Failed to show information, error: {error}',
            ['error' => $errorexception]
        ),
        MSG_ERROR
    );
}

UiComponent::cardFooter('');
