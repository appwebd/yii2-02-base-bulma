<?php

use app\components\UiComponent;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'About');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">

            <div class="webpage ">';

echo UiComponent::header(
    'home',
    $this->title,
    Yii::t(
        'app',
        'About of this webpage'
    )
);


echo '<p>This is the About page. You may modify the following file to customize its content</p>';
echo '<code>', __FILE__, '</code><br/><br>';


echo Yii::$app->view->render('@app/views/partials/_links_return_to');
echo '
            </div>
            <div class="col-sm-3 "> &nbsp;&nbsp; </div>
        </div>
    </div>
</div>';

