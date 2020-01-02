<?php
/**
 * Permission
 * PHP version 7.2.0
 *
 * @category  View
 * @package   Permission
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

use app\components\UiButtons;
use app\components\UiComponent;
use app\models\Permission;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\search\ActionSearch;
use app\models\search\ControllersSearch;
use app\models\search\ProfileSearch;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $controller_id integer */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pageSize integer */
/* @var $searchModel app\models\search\PermissionSearch */


$this->title = Yii::t('app', Permission::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo Html::beginForm(['permission/index'], 'post');
$uiButtons = new UiButtons();
$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-circle fa-2x',
    'is-white',
    $this->title,
    Yii::t(
        'app',
        'This view permit Create, update or delete information related of permission'
    ),
    '111',
    false
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
                    STR_CLASS => GRID_DATACOLUMN,
                    ATTRIBUTE => Permission::PROFILE_ID,
                    FILTER => ProfileSearch::getProfileListSearch(Permission::TABLE),
                    VALUE => 'profile.profile_name',
                    FORMAT => 'raw',
                ],
                [
                    STR_CLASS => GRID_DATACOLUMN,
                    ATTRIBUTE => Permission::CONTROLLER_ID,
                    FILTER => ControllersSearch::getControllersListSearch(
                        Permission::TABLE
                    ),
                    VALUE => 'controllers.controller_name',
                    FORMAT => 'raw',
                ],
                [
                    STR_CLASS => GRID_DATACOLUMN,
                    ATTRIBUTE => Permission::ACTION_ID,
                    FILTER => ActionSearch::getActionListSearch(
                        $controller_id,
                        Permission::TABLE
                    ),
                    VALUE => 'action.action_name',
                    FORMAT => 'raw',
                ],

                [
                    STR_CLASS => GRID_DATACOLUMN,
                    FILTER => $uiComponent->yesOrNoArray(),
                    ATTRIBUTE => Permission::ACTION_PERMISSION,
                    OPTIONS => [STR_CLASS => COLSM1],
                    VALUE => function ($model) {
                        $url = "permission/toggle";
                        $uiComponent = new UiComponent();
                        return Html::a(
                            '<span class="' . $uiComponent->yesOrNoGlyphicon(
                                $model->action_permission
                            ) . '"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'Toggle value active'),
                                'data-value' => $model->action_permission,
                                'data' => [
                                    METHOD => 'post',
                                ],
                                'data-pjax' => 'w0',
                            ]
                        );
                    },
                    FORMAT => 'raw'
                ],
                [
                    'buttons' => $uiButtons->buttonsActionColumn(),
                    'contentOptions' => [STR_CLASS => 'GridView'],
                    HEADER => UiComponent::pageSizeDropDownList($pageSize),
                    'headerOptions' => ['style' => 'color:#337ab7'],
                    'class' => yii\grid\ActionColumn::className(),
                    TEMPLATE => Common::getProfilePermissionString('111'),
                ]
            ]

        ]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\permission\index::GridView::widget',
        MSG_ERROR
    );
}

try {
    $uiButtons = new UiButtons();
    $strFooter = $uiButtons->buttonsAdmin('111', false);
    $uiComponent->cardFooter($strFooter);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\permission\index::uiButtons::buttonsAdmin',
        MSG_ERROR
    );
}


Html::endForm();
