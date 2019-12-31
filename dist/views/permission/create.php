<?php
/**
 * Permission create
 * PHP version 7.2.0
 *
 * @category  View
 * @package   Permission
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

use app\components\UiComponent;
use app\models\Permission;

/* @var $this yii\web\View */
/* @var object $model app\models\Permission */

$this->title = Yii::t('app', Permission::TITLE);

$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = Yii::t('app', 'Create');

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-circle fa-2x',
    'is-white',
    $this->title,
    Yii::t('app', 'Please complete all requested information.'),
    '000',
    false
);

echo $this->renderFile('@app/views/permission/_form.php', ['model' => $model]);
