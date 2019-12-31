<?php

/**
 * Error view handling
 * PHP Version 7.0.0
 *
 * @category  View
 * @package   Error-404
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2018-07-30 19:28:34
 */

use app\models\queries\Bitacora;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var string $name  */
/* @var string $message  */
/* @var Exception $exception */

$this->title = Yii::t('app', 'Error');
$this->params[BREADCRUMBS][] = $this->title;

$error = nl2br(Html::encode($message . ' url: ' . Yii::$app->request->url));
$bitacora = new Bitacora();
$bitacora->register(
    Yii::t(
        'app',
        'Error: {error}',
        ['error' => $error]
    ),
    'app/views/404',
    MSG_ERROR
);
echo '
<div class="webpage ">
    <div class="columns">
        <div class="column box">

            <h1>',  Html::encode($this->title), '</h1>
<br>
<div class="danger error-summary">
    <strong>',
    nl2br(Html::encode($message)),
    '< / strong >
        < / div >
        < br >
        < p >',
    Yii::t(
        ' app ',
        'The above error occurred while the Web server was processing your
        request. We are generating a recordstat us  of this error. Thank you.'
    ),
    '</p>
    <br>
        </div >
    </div >
</div >
';
