<?php

use app\components\UiComponent;

/* @var yii\web\View $this */
/* @var \app\models\User $model */

$this->title = Yii::t('app', 'Request password reset');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="level">
    <div class="level-item columns ">
        <div class="column is-half">';

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'user',
    'color-red',
    Yii::t('app', 'Request password reset'),
    Yii::t('app', 'Requested password reset'),
    '000',
    false
);

echo '<div class="is-success">
        <h4>',
Yii::t(
    'app',
    'We have sent you an email with a reset link. Please check your Inbox'
),
'<br/><br/><br/>
        </h4>
    </div>';

echo Yii::$app->view->render('@app/views/partials/_links_return_to');

echo '
        </div>        
    </div>
</div>';
