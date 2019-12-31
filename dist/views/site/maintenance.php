<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
$this->context->layout = false;
$this->title = Yii::t('app', 'Website in maintenance');
$this->params[BREADCRUMBS][] = $this->title;

?>
<h1>Maintenance</h1>
<div class="webpage ">
    <div class="row">
        <div class="col-sm-12 box">

            <h1><?= Html::encode($this->title) ?></h1>
            <br>
            <p>
                <?= Yii::t(
                    'app',
                    'We are sorry, but the application is currently being maintained.<br>Please try again later.'
                ); ?>
            </p>
            <br>

        </div>
    </div>
</div>

