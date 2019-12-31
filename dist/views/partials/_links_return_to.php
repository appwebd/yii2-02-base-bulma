<?php
/**
 *  Links view "return to" view
 *
 * @package      Links view "return to" view
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 16:49:58
 * @version     1.0
 */

use yii\helpers\Html;

echo '
<div class="container has-text-centered"><br>
        ';
echo Yii::t('app', 'Return to:'), '
        &nbsp;',
Html::a(Yii::t('app', 'Login'), ['/login/']), '.&nbsp; | &nbsp;',
Html::a(Yii::t('app', 'Home'), ['/']), '.',
'<br/><br/></div>';
