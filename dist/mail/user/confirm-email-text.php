<?php
/**
 * @var \yii\web\View $this
 * @var \yii\mail\MessageInterface $message
 * @var app\models\User $model
 */

use yii\helpers\Url;
use yii\helpers\Html;
use app\controllers\BaseController;

$token = BaseController::stringEncode($model->email_confirmation_token);
$url = Url::to(['/login/confirmemail', 'token' => $token], true);
?>

Hello <?= Html::encode($model->username) ?>,

Follow the link below to complete your registration:

<?= Html::a(Html::encode($url), $url) ?>

