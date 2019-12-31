<?php

use app\components\UiComponent;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\ContactForm */

$this->title = Yii::t('app', 'Contact');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp;&nbsp; </div>
        <div class="col-sm-6 box">
            <div class="webpage ">';

echo UiComponent::header('user', $this->title, '');
?>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')) :
    ?>

    <div class="alert alert-success">
        <?= Yii::t('app', 'Thank you for contacting us.
                        We will respond as soon as possible.'); ?>
    </div>

<?php else :
    ?>

    <p class="text-justify textSubHeader">
        <?= Yii::t('app', 'We will be very happy to answer all your business inquiries,
                            please complete the following form to contact us.
                            We will respond as soon as possible. Thank you very much.'); ?>
    </p>

    <p class="text-justify textSubHeader">
        Note that if you turn on the Yii debugger, you should be able
        to view the mail message on the mail panel of the debugger.
        <?php if (Yii::$app->mailer->useFileTransport) : ?>
            Because the application is in development mode, the email is not sent but saved as
            a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                                                                                                Please configure the
            <code>useFileTransport</code> property of the <code>mail</code>
            application component to be false to enable email sending.
        <?php endif; ?>
    </p>

    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'subject') ?>
    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
    'template' => '<div class="row">
                                               <div class="col-lg-3">{image}</div>
                                               <div class="col-lg-6">{input}</div>
                                           </div>',
]);

    echo '<div class="form-group">';
    echo Html::submitButton(
        Yii::t('app', 'Submit'),
        ['class' => 'btn btn-primary', 'name' => 'contact-button']
    );
    echo '</div>';

    ActiveForm::end(); ?>

<?php endif; ?>
<?= Yii::$app->view->render('@app/views/partials/_links_return_to');
echo '
            </div>
        </div>
        <div class="col-sm-3 "> &nbsp;&nbsp; </div>
    </div>
</div>
<br>';

