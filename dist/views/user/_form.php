<?php
/**
 * User _form
 *
 * @package     _form Update/create information for table user
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 23:03:06
 * @version     1.0
 */

use app\components\UiButtons;
use app\models\Profile;
use yii\bootstrap\ActiveForm;
use app\components\UiComponent;

/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$uiComponent = new UiComponent();

$form = ActiveForm::begin(
    [
        'id' => 'form-user',
        'method' => 'post',
        'options' => ['class' => 'form-vertical webpage'],
    ]
);

echo '<strong class="formTitle">User identification</strong><br/><br/>';

echo HTML_ROW_DIV6;

echo $form->field($model, 'firstName')->textInput(
    [
        AUTOFOCUS => AUTOFOCUS,
        MAXLENGTH => true,
        PLACEHOLDER => 'First name for example John',
        STR_CLASS => INPUT,
        TABINDEX => 2,
    ]
)->label();

echo HTML_DIV_CLOSE_DIV6_OPEN;

echo $form->field($model, 'lastName')->textInput(
    [

        AUTOFOCUS => AUTOFOCUS,
        MAXLENGTH => true,
        STR_CLASS => INPUT,
        TABINDEX => 3,
        PLACEHOLDER => 'Last name for example Doe'
    ]
)->label();

echo HTML_DIV_CLOSEX2, HTML_ROW_DIV6;

echo $form->field($model, 'telephone')->textInput(
    [
        AUTOFOCUS => AUTOFOCUS,
        PATTERN => PATTERN_PHONE,
        PLACEHOLDER => 'Phone number ex. 56 9 12345678',
        STR_CLASS => INPUT,
        TYPE => 'tel',
        TABINDEX => 4,

    ]
)->label()->hint('Format phone:' . PATTERN_PHONE);

echo HTML_DIV_CLOSE_DIV6_OPEN;

echo $form->field($model, 'email')->input(
    'email',
    [
        AUTOFOCUS => AUTOFOCUS,
        PLACEHOLDER => Yii::t('app', 'john.doe@domain.com'),
        STR_CLASS => INPUT,
        TABINDEX => 5,

    ]
)->label();

echo HTML_DIV_CLOSEX2;

echo '<br/><strong class="formTitle">User and profile account Properties</strong><br/><br/>';

echo HTML_ROW_DIV6;
echo $form->field($model, 'username')->textInput(
    [
        AUTOFOCUS => AUTOFOCUS,
        MAXLENGTH => true,
        PLACEHOLDER => 'User account',
        STR_CLASS => INPUT,
        TABINDEX => 6,
    ]
)->label();

echo HTML_DIV_CLOSE_DIV6_OPEN;
echo $form->field($model, 'password')->passwordInput(
    [
        AUTOFOCUS => AUTOFOCUS,
        MAXLENGTH => true,
        STR_CLASS => INPUT,
        TABINDEX => 7
    ]
)->label()
    ->hint(Yii::t('app', 'When entering a password, it will be updated for this user account'));

echo HTML_DIV_CLOSEX2, HTML_ROW_DIV6;

$items = Profile::getProfileList();
echo $form->field($model, 'profile_id')->radioList(
    $items,
    [
        AUTOFOCUS => AUTOFOCUS,
        'prompt' => Yii::t('app', 'Select Profile'),
        TABINDEX => 8,
    ]
);

echo HTML_DIV_CLOSE_DIV6_OPEN;
echo '<br>';
echo $form->field($model, 'active')->checkbox(
    [
        AUTOFOCUS => AUTOFOCUS,
        LABEL => '&nbsp; Active?',
        TABINDEX => 9,
        UNCHECK => 0,
    ]
);

echo HTML_DIV_CLOSEX2;
echo HTML_DIV_CLOSE;
echo '<div class=\'form-group\'>';
$buttons = new UiButtons();
$strButtons = $buttons->buttonsCreate(10);
$uiComponent->cardFooter($strButtons );
echo $form->errorSummary($model, array(STR_CLASS => "error-summary"));
echo '</div>';
ActiveForm::end();
