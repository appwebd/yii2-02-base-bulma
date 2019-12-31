<?php
/**
 * Profiles Index
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


use app\components\UiComponent;
use app\components\UiButtons;
use app\models\Profile;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var object $dataProviderProfile app\controllers\ProfileController */
/* @var int $pageSize app\controllers\ProfileController */
/* @var object $searchModelProfile app\controllers\ProfileController */

$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN_COL_SM_8;
echo Html::beginForm(['profile/index'], 'post');

$uiButtons = new UiButtons();
$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-user fa-2x',
    'is-white',
    $this->title,
    Yii::t(
        'app',
        'General administration view'
    ),
    '011',
    false
);

try {
    echo GridView::widget(
        [
        'dataProvider' => $dataProviderProfile,
        'filterModel' => $searchModelProfile,
        'filterSelector' => 'select[name="per-page"]',
        'layout' => GRIDVIEW_LAYOUT,
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'rowOptions' => function ($model) {
            return [
                'onclick' => 'js:selectItem("'. $model->profile_id   . '","' .
                                                $model->profile_name . '","' .
                                                $model->active    . '")',
            ];
        },
        'columns' => [
            [
                STR_CLASS => 'yii\grid\CheckboxColumn',
                OPTIONS => [STR_CLASS => 'width10px'],
                VISIBLE => Common::getProfilePermission(ACTION_DELETE)
            ],
            Profile::PROFILE_NAME,
            [
                ATTRIBUTE =>  Profile::ACTIVE,
                FILTER => UiComponent::yesOrNoArray(),
                FORMAT=>'raw',
                OPTIONS => [STR_CLASS=> COLSM1],
                STR_CLASS => yii\grid\DataColumn::className(),
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
            ],
            [
                'buttons' => UiButtons::buttonsActionColumn(),
                'contentOptions' => [STR_CLASS => 'GridView'],
                HEADER => UiComponent::pageSizeDropDownList($pageSize),
                'headerOptions' => ['style' => 'color:#337ab7'],
                STR_CLASS => yii\grid\ActionColumn::className(),
                TEMPLATE => Common::getProfilePermissionString('001'),
            ]
        ]
        ]
    );
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\profile\index::GridView::widget',
        MSG_ERROR
    );
}

echo '<br/><br/>';
try {
    $uiButtons = new UiButtons();
    $strButtons = $uiButtons->buttonsAdmin('011', false);
    $uiComponent->cardFooter($strButtons);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\profile\index::GridView::widget',
        MSG_ERROR
    );
}


echo Html::endForm();
echo HTML_WEBPAGE_CLOSE_OPEN_COL_SM_4;
$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    '',
    'is-white',
    'Information Input',
    Yii::t(
        'app',
        'Please complete all requested information.'
    ),
    '000',
    false
);


$form = ActiveForm::begin(
    [
        'id' => 'form-profile',
        'method'  => 'post',
        'options' => [STR_CLASS => 'form-vertical webpage'],
    ]
);

$model = new Profile();
echo $this->render(
    '_form',
    [
        'model' => $model,
        'form' => $form
    ]
);


echo UiComponent::HTML_CARD_FOOTER_OPEN,
$uiButtons->buttonSave(9), '&nbsp;', $uiButtons->buttonRefresh('New / Refresh'),
     UiComponent::HTML_CARD_FOOTER_CLOSE;

ActiveForm::end();


echo HTML_WEBPAGE_CLOSE;


$script = <<< JS
function selectItem(profile_id, profile_name, active) {

    var oprofile_id   = document.getElementById('profile-profile_id');
    var oprofile_name = document.getElementById('profile-profile_name');
    var oactive = document.getElementById('profile-active');

    oprofile_id.value = profile_id;
    oprofile_name.value = profile_name;

    if (active) {
        oactive.checked = true;
    } else {
        oactive.checked = false;
    }
}

JS;
$this->registerJs($script, View::POS_BEGIN);
