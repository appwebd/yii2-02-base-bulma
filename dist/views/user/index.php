<?php
/**
 * User
 *
 * @package   Index of user
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright (C) Copyright - Web Application development
 * @license   Private license
 * @link      https://appwebd.github.io
 * @date      2018-07-30 14:27:11
 * @version   1.0
 */

use app\components\UiButtons;
use app\components\UiComponent;
use app\models\Profile;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\search\ProfileSearch;
use app\models\User;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var object $searchModelUser app\models\search\UserSearch */
/* @var object $dataProviderUser yii\data\ActiveDataProvider */
/* @var int $pageSize Number of rows to show in the gridView object */

$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo Html::beginForm(['user/index'], 'post');

$uiComponent = new UiComponent();
$uiComponent->headerAdmin(
    'user',
    $this->title,
    Yii::t('app', 'This view permit Create a new User, update or delete information related of user'),
    'user',
    '111',
    false
);

try {
    echo GridView::widget(
        [
            'dataProvider' => $dataProviderUser,
            'filterModel' => $searchModelUser,
            'layout' => '{items}{summary}{pager}',
            'filterSelector' => 'select[name="per-page"]',
            'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
            'columns' => [

                [
                    STR_CLASS => 'yii\grid\CheckboxColumn',
                    'options' => [STR_CLASS => 'width:10px'],
                ],
                User::USERNAME,
                User::FIRSTNAME,
                User::LASTNAME,
                User::EMAIL,
                [
                    STR_CLASS => GRID_DATACOLUMN,
                    ATTRIBUTE => User::PROFILE_ID,
                    FILTER => ProfileSearch::getProfileListSearch('user'),
                    VALUE => function ($model) {
                        $profile_name = Profile::getProfileName($model->profile_id);
                        return UiComponent::badgetStatus(
                            $model->profile_id, $profile_name
                        );
                    },
                    FORMAT => 'raw',
                ],
                [
                    STR_CLASS => GRID_DATACOLUMN,
                    FILTER => UiComponent::yesOrNoArray(),
                    ATTRIBUTE => User::ACTIVE,
                    OPTIONS => [STR_CLASS => COLSM1],
                    VALUE => function ($model) {
                        return UiComponent::yesOrNo($model->active);
                    },
                    FORMAT => 'raw'
                ],
                [
                    'buttons' => UiButtons::buttonsActionColumn(),
                    'contentOptions' => [STR_CLASS => 'GridView'],
                    HEADER => UiComponent::pageSizeDropDownList($pageSize),
                    'headerOptions' => ['style' => 'color:#337ab7'],
                    'class'=> yii\grid\ActionColumn::className(),
                    TEMPLATE => Common::getProfilePermissionString(),
                ]
            ]
        ]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\User\index::GridView',
        MSG_ERROR
    );
}

try {
    $buttons = new UiButtons();
    $buttons->buttonsAdmin('111', false);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\User\index::UiComponent::buttonsAdmin',
        MSG_ERROR
    );
}

Html::endForm();
echo HTML_WEBPAGE_CLOSE;

