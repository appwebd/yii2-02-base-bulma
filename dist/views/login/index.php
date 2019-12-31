<?php
/**
 * Login session view
 * PHP Version 7.2
 *
 * @category  Views
 * @package   Login
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2018-11-02 07:30:41
 */

use app\components\UiComponent;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var yii\widgets\ActiveForm $form */
/* @var \app\models\forms\LoginForm $model */

$this->title = Yii::t('app', 'Login');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="level">
    <div class="level-item columns ">
        <div class="column is-half">';


$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-user fa-2x',
    'card-header-background-login is-white',
    $this->title,
    Yii::t(
        'app',
        'Please complete the following fields to start your session:'
    ),
    '000',
    false,
    'has-text-white'
);

$form = ActiveForm::begin(
    [
        ID => 'login-form',
        'fieldClass' => 'app\widgets\ActiveFieldForm',
    ]
);

echo $form->field(
    $model,
    'username',
    [
        OPTIONS => [ICON_LEFT => 'fa-envelope', ICON_RIGHT => STR_FA_CHECK],
    ]
)->textInput(
    [
        AUTOFOCUS => AUTOFOCUS,
        AUTOCOMPLETE => 'off',
        TABINDEX => '1',
        PLACEHOLDER => Yii::t('app', 'User account'),
        REQUIRED => REQUIRED,
        STR_CLASS => 'input is-half',
        TITLE => 'The user account is required information!',
        X_MOZ_ERROR_MESSAGE => 'The user account is required information!'
    ]
)->label(false),

BREAK_LINE,

$form->field(
    $model,
    'password',
    [
    OPTIONS => [ ICON_LEFT => 'fa-lock', ICON_RIGHT => ''],
    ]
)->passwordInput(
    [
        AUTOFOCUS => AUTOFOCUS,
        AUTOCOMPLETE => 'off',
        PLACEHOLDER => Yii::t('app', 'Password'),
        REQUIRED => REQUIRED,
        STR_CLASS => INPUT,
        TABINDEX => '2',
        TITLE => Yii::t('app', 'The password is required information!'),
        X_MOZ_ERROR_MESSAGE => Yii::t(
            'app',
            'The password is required information!'
        )
    ]
)->label(false),

BREAK_LINE,

$form->field($model, 'rememberMe')->checkbox(
    [
    TITLE => 'We don\'t recommend this in shared computers.',
    AUTOFOCUS => AUTOFOCUS,
    TABINDEX => '3'
    ]
),

BREAK_LINE,
'<div class="control">
    &nbsp;
</div>',

Html::submitButton(
    Yii::t('app', 'Submit'),
    [
        STR_CLASS => 'button is-primary',
        'name' => 'login-button',
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => '4'
    ]
);

ActiveForm::end();


$footer = BREAK_LINE .
    '<div class="container has-text-centered"><br>&nbsp;' .
    Html::a(Yii::t('app', 'forget your password?'), Url::to(['/password/index'])) .
    ' &nbsp; | &nbsp;' .
    Yii::t('app', 'You do not have an account?') .
    '&nbsp;' .
    Html::a(Yii::t('app', 'Signup'), Url::to(['/signup/index'])) .
    BREAK_LINE . BREAK_LINE . HTML_DIV_CLOSE;

$uiComponent->cardFooter($footer);



echo '
        </div>
    </div>
</div>';
