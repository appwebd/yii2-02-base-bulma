<?php
/**
 * User Create/Update
 * PHP version 7.0.0
 *
 * @category  View
 * @package   User
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

use app\components\UiComponent;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var string $titleView Title View */

$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = Yii::t('app', $titleView);

echo HTML_WEBPAGE_OPEN;

$uiComponent = new UiComponent();
$uiComponent->header(
    'user',
    $this->title,
    Yii::t(
        'app',
        'Please complete all requested information.'
    )
);

echo $this->renderFile('@app/views/user/_form.php', ['model'=>$model]);
echo HTML_WEBPAGE_CLOSE;
