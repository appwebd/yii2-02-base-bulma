<?php

use app\components\UiComponent;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var object $form yii\bootstrap\ActiveForm */
/* @var object $model \app\models\forms\PasswordResetForm */

$this->title = Yii::t('app', 'Reset password');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">

            <div class="webpage ">';

$uiComponent = new UiComponent();
$uiComponent->header(
    'user',
    $this->title,
    Yii::t(
        'app',
        'Please, choose your new password'
    )
);


$form = ActiveForm::begin([
    'id' => 'reset-password-form',
    'options' => ['class' => 'form-horizontal webpage'],
]);

echo $form->field($model, 'password')->passwordInput();

echo '
                <div class="form-group">
                    <div class="help-block text-justify">';
echo Yii::t(
    'app',
    'Your new password will be saved in your user account.'
);
echo '
                    </div>';

echo Html::submitButton(
    Yii::t('app', 'Save'),
    ['class' => 'btn btn-primary']
);

echo '&nbsp;
                </div>';

ActiveForm::end();

echo Yii::$app->view->render('@app/views/partials/_links_return_to');
echo '
            </div>
            <div class="col-sm-3 "> &nbsp;&nbsp; </div>
        </div>
    </div>
</div>';
