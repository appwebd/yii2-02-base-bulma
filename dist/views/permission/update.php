<?php
/**
 * Permission
 *
 * @package     Update of Permission
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 19:28:34
 * @version     1.0
 */

use app\components\UiComponent;
use app\models\Permission;

/* @var $this yii\web\View */
/* @var $model app\models\Permission */

$this->title = Yii::t('app', Permission::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = Yii::t('app', 'Update');

echo UiComponent::cardHeader(
    'fas fa-circle fa-2x',
    'is-white',
    $this->title,
    Yii::t('app', 'Please complete all requested information.'),
    '0000',
    false
);

echo $this->render('_form', ['model' => $model,]);
