<?php
/**
 * Email confirmation failed view
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

$this->title = Yii::t('app', 'Email confirmation');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="level">
    <div class="level-item columns">
        <div class="column is-half">';

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-envelope fa-2x',
    'card-header-background-gray',
    $this->title,
    '',
    '000',
    false
);

echo '<div class="has-text-warning has-text-centered">',
Yii::t('app', 'Email confirmation failed'),
'<br/><br/></div>';

$footer = Yii::$app->view->render('@app/views/partials/_links_return_to');
UiComponent::cardFooter($footer);

echo '
        </div>
    </div>
</div>';
