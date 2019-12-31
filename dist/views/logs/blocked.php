<?php
/**
 * Blocked IP Address
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
use app\models\Blocked;
use app\models\queries\Bitacora;
use app\models\search\BlockedSearch;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BlockedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Blocked::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'remove-circle',
    'is-white',
    $this->title,
    Yii::t(
        'app',
        'This view shows the IP addresses that have been blocked for security or administrative reasons.'
    ),
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
                    STR_CLASS => 'yii\grid\CheckboxColumn',
                    'options' => [
                        STR_CLASS => 'width10px'
                    ]
                ],
                [
                    STR_CLASS => yii\grid\DataColumn::className(),
                    ATTRIBUTE => Blocked::ID,
                    OPTIONS => [STR_CLASS => 'col-sm-1'],
                    FORMAT => 'raw'
                ],
                Blocked::IPV4_ADDRESS,
                Blocked::DATE,
                [
                    STR_CLASS => GRID_DATACOLUMN,
                    ATTRIBUTE => Blocked::STATUS_ID,
                    FILTER => BlockedSearch::getStatusListSearch(),
                    VALUE => Blocked::STATUS_STATUS_NAME,
                    FORMAT => 'raw',
                ],
            ]
        ]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'views\logs\blocked::Gridview',
        MSG_ERROR
    );
}

$uiComponent->cardFooter('');
