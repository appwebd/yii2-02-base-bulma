<?php
/**
 * Signed Up message information view
 *
 * @package     Signed Up message information view
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 16:49:58
 * @version     1.0
 */

use app\components\UiComponent;

/* @var yii\web\View $this */
/* @var \app\models\User $model */

$this->title = Yii::t('app', 'Signed Up');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="level">
    <div class="level-item columns">
        <div class="column is-half ">';

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-user fa-2x',
    'card-header-background-gray',
    Yii::t(
        'app',
        'Thanks for your registration'
    ),
    '',
    '000',
    false
);

echo '<p><br>';
echo Yii::t(
    'app',
    'We have sent an email with a link for your confirmation, please check your inbox'
);

echo '<br><br></p>';
$footer = Yii::$app->view->render('@app/views/partials/_links_return_to');
UiComponent::cardFooter($footer);

echo '
        </div>
    </div>
</div>
<br><br>';
