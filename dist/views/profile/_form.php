<?php
/**
 * Profiles
 *
 * @category  view
 * @package   form of Profile
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private license
 * @link      https://appwebd.github.io
 * @date      2019-04-20 22:47:04
 * @version   SVN: $Id$
 * @php       version 7.2
*/

use app\models\Profile;
use app\components\UiComponent;
use app\components\UiButtons;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    Profile::ICON,
    'is-white',
    $this->title,
    Yii::t(
        'app',
        'Please complete all requested information.'
    ),
    '000',
    false
);


if ($model->isNewRecord) {
    $model->active = 1;
}

$form = ActiveForm::begin(
    [
        'id' => 'form-profile',
        'method' => 'post',
        'options' => ['class' => 'form-vertical '],
    ]
);

echo $form->field($model, Profile::PROFILE_ID)->hiddenInput(
    [
        VALUE => $model->profile_id,
    ]
)->label(false);


// 99
echo $form->field(
    $model,
    Profile::PROFILE_NAME
)->textInput(
    [
        AUTOFOCUS => AUTOFOCUS,
        AUTOCOMPLETE => 'off',
        MAXLENGTH => true,

        REQUIRED => REQUIRED,
        STR_CLASS => INPUT,
        TABINDEX => '1',

    ]
)->label();


// 100

echo $form->field(
    $model,
    Profile::ACTIVE
)->checkbox(
    [
        AUTOFOCUS => AUTOFOCUS,
        AUTOCOMPLETE => 'off',
        PLACEHOLDER => 'Active',
        TABINDEX => '2',
        UNCHECK=>0,
    ]
)->label();

echo '<br>
    <div class=\'form-group\'>',
      $form->errorSummary($model, array(STR_CLASS => "error-summary"));

echo HTML_DIV_CLOSE;

$uiButtons = new UiButtons();
$buttons = $uiButtons->buttonsCreate(3);
$uiComponent->cardFooter($buttons);


ActiveForm::end();

echo HTML_DIV_CLOSE;
