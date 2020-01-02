<?php
/**
 * Profile
 * PHP version 7.2.0
 *
 * @category  View
 * @package   Profile
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\Profile;
use app\models\queries\Bitacora;
use yii\widgets\DetailView;
use app\components\UiButtons;

/* @var $this yii\web\View */
/* @var object $model app\models\Profile */

$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = $model->profile_id;

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    Profile::ICON,
    'is-white',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of Profiles'),
    '0000',
    false

);

try {
    echo DetailView::widget([
        'model' => $model,
        OPTIONS => [STR_CLASS => DETAILVIEW_CLASS],
        'attributes' => [
            Profile::PROFILE_ID,
            Profile::PROFILE_NAME,
            [
                ATTRIBUTE => Profile::ACTIVE,
                OPTIONS => [STR_CLASS => COLSM1],
                VALUE => function ($model) {
                    $uiComponent = new UiComponent();
                    return $uiComponent ->yesOrNo($model->active);
                },
                FORMAT => 'raw'
            ],
        ],
    ]);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\profile\view',
        MSG_ERROR
    );
}


try {
    $uiButtons = new UiButtons();
    $strButtons = $uiButtons->buttonsViewBottom($model->profile_id,'111');
    $uiComponent->cardFooter($strButtons);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\profile\view::cardfooter',
        MSG_ERROR
    );
}
