<?php
/**
 * User View
 * PHP version 7.2.0
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

use app\components\UiButtons;
use app\components\UiComponent;
use app\models\Profile;
use app\models\queries\Bitacora;
use app\models\User;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];

echo HTML_WEBPAGE_OPEN;

$uiComponent = new UiComponent();
$uiComponent->header(
    'user',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of User')
);

try {
    echo DetailView::widget([
        'model' => $model,
        OPTIONS => [STR_CLASS => DETAILVIEW_CLASS],
        'attributes' => [
            User::USERNAME,
            User::FIRSTNAME,
            User::LASTNAME,
            User::EMAIL,
            User::TELEPHONE,

            [
                ATTRIBUTE => User::PROFILE_ID,
                HEADER => Yii::t('app', 'Profile'),
                VALUE => function ($model) {
                    return Profile::getProfileName($model->profile_id);
                },
            ],
            User::IPV4_ADDRESS_LAST_LOGIN,
            [
                ATTRIBUTE => User::ACTIVE,
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
                FORMAT => 'raw'
            ],
        ],
    ]);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register($exception, '@app\views\User\view::DetailView', MSG_ERROR);
}
$buttons = new UiButtons();
$buttons->buttonsViewBottom($model);
echo HTML_WEBPAGE_CLOSE;
