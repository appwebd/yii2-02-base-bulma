<?php
/**
 * Profile
 * PHP version 7.2.0
 *
 * @category  View
 * @package   Profile
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

use app\components\UiButtons;
use app\components\UiComponent;
use app\models\Profile;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var object $dataProviderProfile yii\data\ActiveDataProvider */
/* @var int $pageSize */
/* @var  object $searchModelProfile app\models\search\ProfileSearch */

$template = Common::getProfilePermissionString();
$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo Html::beginForm(['profile/index'], 'post');

$uiButtons = new UiButtons();
$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    Profile::ICON,
    'white',
    $this->title,
    Yii::t(
        'app',
        'This view permit Create a new User, update or delete information related of user'
    )
);

try {
    echo GridView::widget(
        [
            'dataProvider' => $dataProviderProfile,
            'filterModel' => $searchModelProfile,
            'layout' => GRIDVIEW_LAYOUT,
            'filterSelector' => 'select[name="per-page"]',
            'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
            'columns' => [
                [
                    STR_CLASS => GRID_CHECKBOXCOLUMN,
                    OPTIONS => [STR_CLASS => 'width10px']
                ],
                [
                    ATTRIBUTE => Profile::PROFILE_NAME,
                    FORMAT => 'raw',
                    OPTIONS => [STR_CLASS => MAXWIDTH],
                    STR_CLASS => yii\grid\DataColumn::class,
                ],

                [
                    ATTRIBUTE => Profile::ACTIVE,
                    FILTER => $uiComponent->yesOrNoArray(),
                    FORMAT => 'raw',
                    OPTIONS => [STR_CLASS => 'col-sm-1'],
                    STR_CLASS => yii\grid\DataColumn::class,
                    VALUE => function ($model) {
                        $uiComponent = new UiComponent();
                        return $uiComponent->yesOrNo($model->active);
                    },
                ],
                [
                    'buttons' => $uiButtons->buttonsActionColumn(),
                    'contentOptions' => [STR_CLASS => 'GridView'],
                    HEADER => UiComponent::pageSizeDropDownList($pageSize),
                    'headerOptions' => ['style' => 'color:#337ab7'],
                    'class' => yii\grid\ActionColumn::class,
                    TEMPLATE => Common::getProfilePermissionString('111'),
                ]
            ]
        ]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->registerAndFlash(
        $exception,
        '@app\views\profile\index',
        MSG_ERROR
    );
}

try {
    $uiButtons = new UiButtons();
    $strButtons = $uiButtons->buttonsAdmin('111', false);
    $uiComponent->cardFooter($strButtons);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\profile\index::cardFooter',
        MSG_ERROR
    );
}

echo Html::endForm();
