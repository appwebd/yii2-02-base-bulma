<?php
use yii\helpers\Html;
use app\models\forms\PasswordResetRequestForm;

/* @var $this yii\web\View */
/* @var object $model app\models\User */

$token = PasswordResetRequestForm::generateToken($model->password_reset_token, $model->user_id);

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['password/reset', 'token' => $token]);

?>
<div class="password-reset">
    <p>Hello <?= Html::encode($model->firstName .' ' .$model->lastName ) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
