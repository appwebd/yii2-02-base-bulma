<?php
/**
 * +----------------------------------------------------------------------------+
 * | Web applications Development  • Business Process Management consultant     |
 * +----------------------------------------------------------------------------+
 * | @Authors   : Patricio Rojas Ortiz <patricio-rojaso@outlook.com>            |
 * | @Copyright : Copyright (C) Web Application development                     |
 * | @Homepage  : https://appwebd.github.io                                     |
 * | @Date      : 5/25/18 4:59 PM                                               |
 * +----------------------------------------------------------------------------+
 * | For  the  full  copyright and license information, please view the LICENSE |
 * | file that was distributed with this source code.                           |
 * |                                                                            |
 * | If  you  did not receive a copy of the license and are unable to obtain it |
 * | through the world-wide-web, please send an email to                        |
 * | patricio-rojaso@outlook.com so we can send you a copy immediately.         |
 * +----------------------------------------------------------------------------+
 */

use app\components\BulmaMenu;
use yii\helpers\Html;
use yii\helpers\Url;

$menu = new BulmaMenu();
$menu->navbarBegin();

?>

<!--
<div class="navbar-star">
    <div class="navbar-item">
    </div>
</div>
-->
<?php


echo '
<div class="navbar-end">
    <div class="navbar-item">',
Html::a(Yii::t('app', 'Sign up'), Url::to(['signup/index']), [STR_CLASS => 'button is-primary']),
'&nbsp;',
Html::a(Yii::t('app', 'Login'), Url::to(['login/index']), [STR_CLASS => 'button']),
'&nbsp;
    </div>
</div>';



$menu->navBarEnd();
?>
