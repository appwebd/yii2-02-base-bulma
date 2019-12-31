<?php
/**
 * Layout/main.php
 * PHP version 7.2.0
 *
 * @category  View
 * @package   Layout
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use app\models\queries\Bitacora;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
<head>
    <meta charset="<?php echo Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?php echo Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="body">


<?php

$this->beginBody();

if (Yii::$app->user->isGuest) {
    echo $this->renderFile('@app/views/partials/_menuGuest.php');
} else {
    echo $this->renderFile('@app/views/partials/_menuAdmin.php');
}

try {
    echo '<nav class="breadcrumb webpage" aria-label="breadcrumbs ">',
        Breadcrumbs::widget(
            [
		    'links' => isset($this->params[BREADCRUMBS]) ?
		        $this->params[BREADCRUMBS] : [],
            ]
    ),
    '</nav>';
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'views\layout\main::Breadcrumbs',
        MSG_ERROR
    );
}

echo '<div class="webpage">';
try {
    echo Alert::widget();
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->registerAndFlash(
        $exception,
        'app\views\layout\main::Alert',
        MSG_ERROR
    );
}
echo HTML_DIV_CLOSE,
    '<div class="webpage">',
    $content,
    HTML_DIV_CLOSE,
    $this->renderFile('@app/views/partials/_footer.php');

$this->endBody();

?>
</body>
</html>
<?php $this->endPage() ?>
