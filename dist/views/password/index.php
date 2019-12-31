<?php

use app\components\UiComponent;
use app\models\forms\PasswordResetRequestForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var yii\widgets\ActiveForm $form */
/* @var \app\models\forms\PasswordResetRequestForm $model */

$this->title = Yii::t('app', 'Request password reset');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="level ">
    <div class="level-item columns ">
        <div class="column is-half ">';

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-envelope fa-2x',
    'card-header-background-gray',
    $this->title,
    '',
    '000',
    false
);

echo '<div class="block">
                    <p class="has-text-justified help-block">',
Yii::t(
    'app',
    'Please, write your registered mail in this platform to reset your password'
),
'</p>',
'</div>';

$form = ActiveForm::begin([
    'id' => 'request-password-reset-form',
    'fieldClass' => 'app\widgets\ActiveFieldForm',
]);

echo $form->field($model, PasswordResetRequestForm::EMAIL, [
    'options' => ['icon-left' => 'fa-envelope', 'icon-right' => 'fa-check'],
])->textInput([
    'placeholder' => Yii::t(
        'app',
        ' valid email account, Ex: account@domain.com'
    ),
    STR_CLASS => 'input',
    REQUIRED => REQUIRED,
    TITLE => Yii::t('app', 'Email is required information!'),
    'x-moz-errormessage' => Yii::t('app', 'Email is required information!')
])->label(false);

echo '<div class="block">
                    <div class="content is-small help-block">';

echo Yii::t(
    'app',
    'A link to reset the password will be sent to your email account.'
);

echo '
                </div>';

echo Html::submitButton(
    Yii::t('app', 'Submit'),
    ['class' => 'button is-primary']
);

echo '&nbsp;<br>
            </div>';

ActiveForm::end();

$footer = Yii::$app->view->render('@app/views/partials/_links_return_to');
$uiComponent->cardFooter($footer);

echo '
        </div>
    </div>
</div>';
