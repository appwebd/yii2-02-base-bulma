<?php
/**
 * Permission View
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

use app\components\UiButtons;
use app\components\UiComponent;
use app\models\Permission;
use app\models\queries\Bitacora;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var object $model app\models\Permission */

$this->title = Yii::t('app', Permission::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];

$uiComponent = new UiComponent();
$uiComponent->cardHeader(
    'fas fa-circle fa-2x',
    'is-white',
    $this->title,
    Yii::t('app', 'Detailed information of permission'),
    '000',
    false
);

try {
    echo DetailView::widget(
        [
            'model' => $model,
            OPTIONS => [STR_CLASS => DETAILVIEW_CLASS],
            'attributes' => [
                [
                    ATTRIBUTE => Permission::PROFILE_NAME,
                    VALUE => function ($model) {
                        return $model->profile->profile_name;
                    },
                ],
                [
                    ATTRIBUTE => Permission::CONTROLLER_ID,
                    VALUE => function ($model) {
                        return $model->controllers->controller_name;
                    },
                ],
                [
                    ATTRIBUTE => Permission::ACTION_ID,
                    VALUE => function ($model) {
                        return $model->action->action_name;
                    },
                ],
                [
                    ATTRIBUTE => Permission::ACTION_PERMISSION,
                    VALUE => function ($model) {
                        return UiComponent::yesOrNo($model->action_permission);
                    },
                    FORMAT => 'raw'
                ],
            ],
        ]
    );
} catch (Exception $errorexception) {
    $bitacora = new Bitacora();
    $bitacora->register(
        $exception,
        'app\views\permission\View::DetailView::widget',
        MSG_ERROR
    );
}
$uiButtons = new UiButtons();
$strFooter = $uiButtons->buttonsViewBottom($model->permission_id);
$uiComponent->cardFooter($strFooter);
