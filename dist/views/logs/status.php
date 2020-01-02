<?php
/**
 * Informative status of events in all the platform
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
use app\models\queries\Bitacora;
use app\models\Status;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Status::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    Status::ICON,
    'is-white',
    $this->title,
    '',
    '000',
    true
);

try {
    echo GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => '{items}{summary}{pager}',
            'filterSelector' => 'select[name="per-page"]',
            'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
            'columns' => [
                [
                    ATTRIBUTE => Status::STATUS_ID,
                    FORMAT => 'raw',
                    OPTIONS => [STR_CLASS => COLSM1],
                    STR_CLASS => GRID_DATACOLUMN,
                ],
                Status::STATUS_NAME,
                [
                    ATTRIBUTE => Status::ACTIVE,
                    FILTER => $uiComponent->yesOrNoArray(),
                    FORMAT => 'raw',
                    OPTIONS => [STR_CLASS => COLSM1],
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => function ($model) {
                        $uiComponent = new UiComponent();
                        return $uiComponent->yesOrNo($model->active);
                    },
                ],

            ]
        ]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->registerAndFlash(
        $exception,
        'app\views\logs\status::Gridview',
        MSG_ERROR
    );
}

$uiComponent->cardFooter('');
