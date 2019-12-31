<?php
/**
 * Class Bitacora
 * PHP Version 7.0.0
 *
 * @category  Models
 * @package   Bitacora
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019  Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/01/18 10:07 PM
 */

namespace app\models\queries;

use app\models\Action;
use app\models\Controllers;
use app\models\Logs;
use app\models\Status;
use Yii;
use yii\db\Exception;

/**
 * Class Bitacora
 * PHP Version 7.0.0
 *
 * @category  Bitacora
 * @package   Models
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019  Copyright - Web Application development
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      11/01/18 10:07 PM
 */
class Bitacora extends Logs
{
    /**
     * Save in table logs all events and activities of this
     * web application and flash message respective
     *
     * @param string  $event        Events or activities
     * @param string  $functionCode Name of function in source code
     * @param integer $statusId     Status_id related to table status
     *
     * @return void
     */
    public function registerAndFlash($event, $functionCode, $statusId)
    {
        $this->register($event, $functionCode, $statusId);
        $badge = Status::getStatusBadge($statusId);
        Yii::$app->session->setFlash($badge, $event);
    }

    /**
     * Save in table logs all events and activities of this web application
     *
     * @param string|array $event        Events or activities
     * @param string       $functionCode Name of function in source code
     * @param integer      $statusId     Status_id related to table status
     *
     * @return void
     */
    public function register($event, $functionCode, $statusId)
    {
        $model = new Logs();
        $model->status_id = $statusId;
        $model->functionCode = $functionCode;

        $error  = $event;
        if (is_array($event)) {
            $error = print_r($event, true);
        }

        $model->event = substr($error, 0, 250);

        $usero = new UserMethods();
        $model->user_id = $usero->getUserId();
        $model->controller_id = $this->getControllerId(Yii::$app->controller->id); // controller name
        $model->action_id = $this->getActionId(Yii::$app->controller->action->id, $model->controller_id); // Action name

        if ($model->controller_id == 0 || $model->action_id == 0) {
            $message = Yii::t(
                'app',
                'Could not save new log information: {error}',
                [
                    ERROR => print_r($model->errors, true)
                ]
            );
            Yii::$app->session->setFlash(ERROR, $message);
        } else {
            $model->user_agent = substr(Yii::$app->request->userAgent, 0, 250);
            $model->ipv4_address = substr(
                Yii::$app->getRequest()->getUserIP(),
                0,
                20
            );
            $model->ipv4_address_int = ip2long($model->ipv4_address);
            $model->confirmed = 0;
            try {
                if ($model->validate()) {
                    $model->save();
                }
            } catch (Exception $exception) {
                $message = Yii::t(
                    'app',
                    'Could not save new log information: {error}',
                    [
                        ERROR => print_r($exception, true)
                    ]
                );
                Yii::$app->session->setFlash(ERROR, $message);
            }
        }
    }

    /**
     * Get controller_id using controllerName to search
     *
     * @param string $controllerName Name of controller
     *
     * @return int Controller ID
     */
    public function getControllerId($controllerName)
    {
        $modelController = Controllers::getControllers($controllerName);
        if ($modelController) {
            $controllerId = $modelController->controller_id;
        } else {
            Controllers::addControllers($controllerName, 'not verified', 1, 0, 1);
            $modelController = Controllers::getControllers($controllerName);
            if ($modelController) {
                $controllerId = $modelController->controller_id;
            } else {
                $message = Yii::t(
                    'app',
                    'Error creating controlller name: {controller_name}',
                    ['controllerName' => $controllerName]
                );
                Yii::$app->session->setFlash(ERROR, $message);
                $controllerId = 0;
            }
        }
        return $controllerId;
    }

    /**
     * Get action_id of table action
     *
     * @param string $actionName  Name of action
     * @param int    $controlleId Controller_id primary key of table controller
     *
     * @return int
     */
    public function getActionId($actionName, $controlleId)
    {
        $modelAction = Action::getAction($actionName, $controlleId);
        if ($modelAction) {
            $actionId = $modelAction->action_id;
        } else {
            try {
                Action::addAction($controlleId, $actionName, 'not verified', 1);
            } catch (Exception $exception) {
                $message = Yii::t(
                    'app',
                    ERROR_MODULE,
                    [
                        MODULE => 'app\models\queries\Bitacora::getActionId',
                        ERROR => $exception
                    ]
                );
                Yii::$app->session->setFlash(ERROR, $message);
            }
            $modelAction = Action::getAction($actionName, $controlleId);
            if ($modelAction) {
                $actionId = $modelAction->action_id;
            } else {
                $mesage = Yii::t(
                    'app',
                    'Error creating action name: {action_name}',
                    ['action_name' => $actionName]
                );
                Yii::$app->session->setFlash(ERROR, $mesage);
                $actionId = 0;
            }
        }
        return $actionId;
    }
}
