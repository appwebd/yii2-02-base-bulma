<?php
/**
 * Permission
 *
 * @package     form of Permission
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 19:28:33
 * @version     1.0
 */

use app\components\UiComponent;
use app\components\UiButtons;
use app\models\Action;
use app\models\Controllers;
use app\models\Permission;
use app\models\Profile;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var object $model app\models\Permission */
/* @var object $form yii\widgets\ActiveForm */

$form = ActiveForm::begin(
    [
        'id' => 'form-permission',
        'method' => 'post',
        'options' => ['class' => 'form-vertical '],
    ]
);


echo HTML_WEBPAGE_OPEN, HTML_COLUMNS;

$items = Profile::getProfileList();
echo $form->field($model, Permission::PROFILE_ID)->RadioList(
    $items,
    [
        PROMPT => Yii::t('app', 'Select Profile'),
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => 1,
        REQUIRED => REQUIRED,
        AUTOCOMPLETE => 'off',
    ]
)->label();
echo BREAK_LINE;
$items = Controllers::getControllersList();
echo $form->field($model, Permission::CONTROLLER_ID)->dropDownList(
    $items,
    [
        PROMPT => Yii::t('app', 'Select Controller'),
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => 2,
        REQUIRED => REQUIRED,
        AUTOCOMPLETE => 'off',
        'onchange' => '
            $.get( "' , Yii::$app->urlManager->createUrl(
            'permission/actiondropdown'
        ),
            '", {id: $(this).val()})
                .done(function( data ) {
                    $( "#' ,
                    Html::getInputId(
                        $model,
                        Permission::ACTION_ID
                    ) , '" ).html( data );
                }
            );'
    ]
);

echo BREAK_LINE;
if ($model->isNewRecord) {
    echo $form->field($model, Permission::ACTION_ID)->dropDownList(
        [
            1 => 'que permiso falta definir aqui'
        ],
        [
            AUTOFOCUS => AUTOFOCUS,
            TABINDEX => 3,
            REQUIRED => REQUIRED,
            PROMPT => Yii::t('app', 'Select Action'),
        ]
    )->label();

    $model->action_permission = 1;
} else {
    $items = Action::getActionListById($model->controller_id);
    echo $form->field($model, Permission::ACTION_ID)->dropDownList(
        $items,
        [
            PROMPT => Yii::t('app', 'Select Action'),
            AUTOFOCUS => AUTOFOCUS,
            TABINDEX => 3,
            REQUIRED => REQUIRED,
        ]
    )->label();
}
echo BREAK_LINE;
echo $form->field($model, Permission::ACTION_PERMISSION)->checkbox(
    [
        UNCHECK => 0,
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => 4,
    ]
);


echo HTML_DIV_CLOSEX2, HTML_WEBPAGE_CLOSE, '<br/>';
echo $form->errorSummary($model, array(STR_CLASS => "error-summary"));

$uiButtons = new UiButtons();
$strFooter = $uiButtons->buttonsCreate(5);
$uiComponent = new UiComponent();
$uiComponent->cardFooter($strFooter);

ActiveForm::end();
